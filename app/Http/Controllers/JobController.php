<?php

namespace App\Http\Controllers;

use App\Jobs\DomainScrapJob;
use App\Models\City;
use App\Models\Country;
use App\Models\Keyword;
use App\Models\Niche;
use App\Models\SubNiche;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        $keywords = Keyword::orderBy('name', 'asc')->select('id', 'name')->get();
        $niches = Niche::orderBy('name', 'asc')->select('id', 'name')->get();
        if (auth()->user()->role === \App\Enums\UserRoles::USER) {
            $countries = Country::orderBy('name', 'asc')->select('id', 'name')->get()->random(3);
            $cities = City::orderBy('name', 'asc')->select('id', 'name')->get()->random(3);
            return view('admin.job.index', compact('cities', 'countries', 'keywords', 'niches'));
        }
        $countries = Country::orderBy('name', 'asc')->select('id', 'name')->get();
        $cities = City::orderBy('name', 'asc')->select('id', 'name')->get();

        return view('admin.job.index', compact('cities', 'countries', 'keywords', 'niches'));
    }

    public function start(Request $request)
    {
        $niche = null;
        $sub_niche = null;
        $country = Country::whereIn('id', $request->country_id ?? [])->pluck('name');
        $city = City::whereIn('id', $request->city_id ?? [])->pluck('name');

        $location = $country->merge($city);
        $keywords = explode(',', $request->keyword);
        if (auth()->user()->role === \App\Enums\UserRoles::USER) {
            $keywords = [$keywords[0]];
        }
        $niche_name = null;
        $sub_niche_name = null;
        if ($request->sub_niche) {
            $sub_niche = SubNiche::find($request->sub_niche);
            $keywords = [];
            $keywords = array_merge($keywords, [strtolower($sub_niche->name)]);
            $sub_niche_name = $sub_niche->name;
        } else if ($request->niche) {
            $niche = Niche::find($request->niche);
            $keywords = [];
            $keywords = array_merge($keywords, [strtolower($niche->name)]);
            $niche_name = $niche->name;
        }

        $city_name = count($city) > 1 ? 'All' : $city->first();
        $country_name = count($country) > 1 ? 'All' : $country->first();

        dispatch(new DomainScrapJob($location,$keywords,$request->additional_keyword, $request->year,$niche,$sub_niche,(boolean) $request->country_id,(boolean) $request->city_id,$city_name, $country_name, $niche_name, $sub_niche_name));

        return back()->with('success', 'Your request is being processed.');
    }
}
