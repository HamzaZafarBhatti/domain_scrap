<?php

namespace App\Http\Controllers;

use App\Jobs\AddDomainJob;
use App\Models\City;
use App\Models\Country;
use App\Models\Keyword;
use App\Models\Niche;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DomainController extends Controller
{
    public function index()
    {
        $countries = Country::where('is_active', true)->select('id', 'name')->get();
        $cities = City::where('is_active', true)->select('id', 'name')->get();
        $keywords = Keyword::where('is_active', true)->select('id', 'name')->get();
        $niches = Niche::where('is_active', true)->select('id', 'name')->get();
        return view('admin.domain.index', compact('cities', 'countries', 'keywords', 'niches'));
    }

    public function start(Request $request)
    {
        // $request->validate([
        //     'country_id' => 'required_without:city_id',
        //     'city_id' => 'required_without:country_id',

        // ]);
        $archived_domain_names = array();
        $country = Country::whereIn('id', $request->country_id ?? [])->pluck('name');
        $city = City::whereIn('id', $request->city_id ?? [])->pluck('name');
        $location = $country->merge($city);
        $keywords = explode(',', $request->keyword);
        if (auth()->user()->role === \App\Enums\UserRoles::USER) {
            $keywords = [$keywords[0]];
        }
        if (count($location) > 0) {
            foreach ($location as $loc) {
                foreach ($keywords as $key) {
                    $keyword = str_replace(' ', '', $key);
                    $loc_name = str_replace(' ', '', $loc);
                    $domain =  \strtolower($keyword) . $request->additional_keyword . \strtolower($loc_name) . '.com';
                    $response = Http::withHeaders([
                        'referer' => 'https://web.archive.org/',
                    ])->get('https://web.archive.org/__wb/sparkline', [
                        'output' => 'json',
                        'url' => $domain,
                        'collection' => 'web',
                    ]);
                    $web = $response->json();
                    try{
                        if($web['first_ts'] == null || $web['last_ts'] == null || $web['years'] == [] || $web['status'] == []){
                            continue;
                        }
                        $first_date = Carbon::parse(strtotime($web['first_ts']))->format('Y');
                        $last_date = Carbon::parse(strtotime($web['last_ts']))->format('Y');
                    }catch(Exception){
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
                            }
                        }
                    }
                }
            }
        } else {
            foreach ($keywords as $key) {
                $keyword = str_replace(' ', '', $key);
                $domain =  \strtolower($keyword) . $request->additional_keyword . '.com';
                $response = Http::withHeaders([
                    'referer' => 'https://web.archive.org/',
                ])->get('https://web.archive.org/__wb/sparkline', [
                    'output' => 'json',
                    'url' => $domain,
                    'collection' => 'web',
                ]);
                $web = $response->json();
                try{
                    if($web['first_ts'] == null || $web['last_ts'] == null || $web['years'] == [] || $web['status'] == []){
                        continue;
                    }
                    $first_date = Carbon::parse(strtotime($web['first_ts']))->format('Y');
                    $last_date = Carbon::parse(strtotime($web['last_ts']))->format('Y');
                }catch(Exception){
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
                        }
                    }
                }
            }
        }
        AddDomainJob::dispatch($archived_domain_names);
        return back()->with('domains', $archived_domain_names)->with('keyword',$request->keyword);
    }
}
