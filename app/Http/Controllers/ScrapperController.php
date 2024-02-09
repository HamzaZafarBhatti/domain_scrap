<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ScrapperController extends Controller
{
    public function index()
    {
        $countries = Country::select('id', 'name')->get();
        $cities = City::select('id', 'name')->get();
        return view('admin.scrapper.index', compact('cities', 'countries'));
    }

    public function start(Request $request)
    {
        // return $request;
        // $data['keyword'] = $request->keyword;
        // $data['additional_keyword'] = $request->additional_keyword;
        $archived_domain_names = array();
        $country = Country::find($request->country_id)->name;
        $city = City::find($request->city_id)->name;
        $location = [$country, $city];
        // $keywords = ['realestatezzz, real estate', 'house for sale'];
        $keywords = explode(',', $request->keyword);
        $domains = [];
        foreach ($location as $loc) {
            foreach ($keywords as $key) {
                $keyword = str_replace(' ', '', $key);
                $domains[] = $keyword . $request->additional_keyword . $loc . '.com';
            }
        }
        // return $keywords;
        $domain_year_data = [];
        for ($i = $request->year; $i <= now()->format('Y'); $i++) {
            $years[] = $i;
        }
        // return $years;
        // $years = ['2024', '2023', '2022', '2021', '2020', '2019', '2018', '2017', '2016', '2015', '2014'];
        foreach ($domains as $domain) {
            $domain_name = $domain;
            foreach ($years as $year) {
                $url = 'https://web.archive.org/__wb/calendarcaptures/2?url=' . $domain_name . '&date=' . $year . '&groupby=day';
                $response = Http::get($url);
                if ($response->status() == 200) {
                    $domain_year_data = $response->json();
                    if (!empty($domain_year_data)) {
                        break;
                    }
                }
            }
            if (!empty($domain_year_data)) {
                continue;
            }
            $response = Http::withHeaders([
                'X-RapidAPI-Host' => 'domainr.p.rapidapi.com',
                'X-RapidAPI-Key' => 'ee945fba55msh43c04ba37ae8d39p1e79d0jsn487ddd1f7dad',
            ])->get('https://domainr.p.rapidapi.com/v2/status?mashape-key=d03abf08787645d4a17386782f11b0b7&domain=' . $domain_name);

            if ($response->status() == 200) {
                $data = $response->json();
                if (str_contains($data['status'][0]['status'], 'inactive')) {
                    $archived_domain_names[] = $data['status'][0]['domain'];
                }
            }
        }
        return back()->with('domains', $archived_domain_names);
    }
}
