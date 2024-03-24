<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Domain;
use App\Models\DomainTld;
use App\Models\Keyword;
use App\Models\Niche;
use App\Models\SubNiche;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DomainController extends Controller
{
    public function index()
    {
        $keywords = Keyword::orderBy('name', 'asc')->select('id', 'name')->get();
        $niches = Niche::orderBy('name', 'asc')->select('id', 'name')->get();
        $domain_tlds = DomainTld::orderBy('name')->select('id', 'name')->get();
        if (auth()->user()->role === \App\Enums\UserRoles::USER) {
            $countries = Country::orderBy('name', 'asc')->select('id', 'name')->get()->random(3);
            $cities = City::orderBy('name', 'asc')->select('id', 'name')->get()->random(3);
            return view('admin.domain.index', compact('cities', 'countries', 'keywords', 'niches','domain_tlds'));
        }
        $countries = Country::orderBy('name', 'asc')->select('id', 'name')->get();
        $cities = City::orderBy('name', 'asc')->select('id', 'name')->get();

        return view('admin.domain.index', compact('cities', 'countries', 'keywords', 'niches', 'domain_tlds'));
    }

    public function start(Request $request)
    {
        // $request->validate([
        //     'country_id' => 'required_without:city_id',
        //     'city_id' => 'required_without:country_id',

        // ]);
        $niche = null;
        $sub_niche = null;
        $archived_domain_names = array();
        $country = Country::whereIn('id', $request->country_id ?? [])->pluck('name');
        $city = City::whereIn('id', $request->city_id ?? [])->pluck('name');
        $location = $country->merge($city);
        $keywords = explode(',', $request->keyword);
        if (auth()->user()->role === \App\Enums\UserRoles::USER) {
            $keywords = [$keywords[0]];
        }
        if ($request->sub_niche) {
            $sub_niche = SubNiche::find($request->sub_niche);
            $keywords = [];
            $keywords = array_merge($keywords, [strtolower($sub_niche->name)]);
        } else if ($request->niche) {
            $niche = Niche::find($request->niche);
            $keywords = [];
            $keywords = array_merge($keywords, [strtolower($niche->name)]);
        }
        if(count($request->domain_tlds) > 0){
            $domain_tlds = DomainTld::whereIn('id',$request->domain_tlds)->pluck('name');
        }
        else{
            $domain_tlds = DomainTld::where('name','.com')->pluck('name');
        }
        foreach ($domain_tlds as $tld) {

            if (count($location) > 0) {
                foreach ($location as $loc) {
                    foreach ($keywords as $k_index => $key) {
                        if($k_index > 0){
                            sleep(2);
                        }
                        $keyword = str_replace(' ', '', $key);
                        $loc_name = str_replace(' ', '', $loc);
                        $domain =  \strtolower($keyword) . $request->additional_keyword . \strtolower($loc_name) . $tld;
                        $response = Http::withHeaders([
                            'referer' => 'https://web.archive.org/',
                        ])->get('https://web.archive.org/__wb/sparkline', [
                            'output' => 'json',
                            'url' => $domain,
                            'collection' => 'web',
                        ]);
                        $web = $response->json();
                        try {
                            if ($web['first_ts'] == null || $web['last_ts'] == null || $web['years'] == [] || $web['status'] == []) {
                                continue;
                            }
                            $first_date = Carbon::parse(strtotime($web['first_ts']))->format('Y');
                            $last_date = Carbon::parse(strtotime($web['last_ts']))->format('Y');
                        } catch (Exception) {
                            continue;
                        }
                        if (($request->year - $first_date <= 0) || ($request->year - $last_date <= 0)) {
                            $response = Http::withHeaders([
                                'X-RapidAPI-Host' => 'domainr.p.rapidapi.com',
                                'X-RapidAPI-Key' => 'ee945fba55msh43c04ba37ae8d39p1e79d0jsn487ddd1f7dad',
                            ])->get('https://domainr.p.rapidapi.com/v2/status?mashape-key=d03abf08787645d4a17386782f11b0b7&domain=' . $domain);

                            if ($response->status() == 200) {
                                $data = $response->json();
                                if (str_contains($data['status'][0]['status'], 'inactive')) {
                                    $archived_domain_names[] = $data['status'][0]['domain'];
                                    Domain::updateOrCreate(['domain_name' => $data['status'][0]['domain']], ['sub_niche_id' => $sub_niche?->id, 'niche_id' => $niche?->id, 'domain_name' => $data['status'][0]['domain']]);
                                }
                            }
                        }
                    }
                }
            } else {
                foreach ($keywords as $k_index => $key) {
                    if($k_index > 0){
                        sleep(2);
                    }
                    $keyword = str_replace(' ', '', $key);
                    $domain =  \strtolower($keyword) . $request->additional_keyword . $tld;
                    $response = Http::withHeaders([
                        'referer' => 'https://web.archive.org/',
                    ])->get('https://web.archive.org/__wb/sparkline', [
                        'output' => 'json',
                        'url' => $domain,
                        'collection' => 'web',
                    ]);
                    $web = $response->json();
                    try {
                        if ($web['first_ts'] == null || $web['last_ts'] == null || $web['years'] == [] || $web['status'] == []) {
                            continue;
                        }
                        $first_date = Carbon::parse(strtotime($web['first_ts']))->format('Y');
                        $last_date = Carbon::parse(strtotime($web['last_ts']))->format('Y');
                    } catch (Exception) {
                        continue;
                    }

                    if (($request->year - $first_date <= 0) || ($request->year - $last_date <= 0)) {
                        $response = Http::withHeaders([
                            'X-RapidAPI-Host' => 'domainr.p.rapidapi.com',
                            'X-RapidAPI-Key' => 'ee945fba55msh43c04ba37ae8d39p1e79d0jsn487ddd1f7dad',
                        ])->get('https://domainr.p.rapidapi.com/v2/status?mashape-key=d03abf08787645d4a17386782f11b0b7&domain=' . $domain);

                        if ($response->status() == 200) {
                            $data = $response->json();
                            if (str_contains($data['status'][0]['status'], 'inactive')) {
                                $archived_domain_names[] = $data['status'][0]['domain'];
                                Domain::updateOrCreate(['domain_name' => $data['status'][0]['domain']], ['sub_niche_id' => $sub_niche?->id, 'niche_id' => $niche?->id, 'domain_name' => $data['status'][0]['domain']]);
                            }
                        }
                    }
                }
            }
        }
        if (empty($archived_domain_names)) {
            $new_key = substr($keywords[0], 0, 3);
            $archived_domain_names = Domain::where('domain_name', 'like', '%' . $new_key . '%')->first();
            if ($archived_domain_names) {
                $archived_domain_names = [$archived_domain_names->domain_name];
            }
        }
        return back()->with('domains', $archived_domain_names)->with('keyword', $request->keyword);
    }
}
