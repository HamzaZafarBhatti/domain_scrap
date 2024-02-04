<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ScrapperController extends Controller
{
    public function index()
    {
        return view('admin.scrapper.index');
    }

    public function start(Request $request)
    {
        $data['keyword'] = $request->keyword;
        $keyword = str_replace(' ', '', $request->keyword);
        $archived_domains = Http::get('https://web.archive.org/__wb/search/anchor?q=(' . $keyword . ')');
        $archived_domains = $archived_domains->object();
        $archived_domain_names = array();
        $years = ['2024'/* , '2023', '2022', '2021', '2020', '2019', '2018', '2017', '2016', '2015', '2014' */];
        foreach ($archived_domains as $domain) {
            $domain_name = $domain->name;
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
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', 'https://domainr.p.rapidapi.com/v2/status?mashape-key=d03abf08787645d4a17386782f11b0b7&domain=' . $domain_name, [
                'headers' => [
                    'X-RapidAPI-Host' => 'domainr.p.rapidapi.com',
                    'X-RapidAPI-Key' => 'ee945fba55msh43c04ba37ae8d39p1e79d0jsn487ddd1f7dad',
                ],
            ]);

            return $response->getBody();
        }
    }
}
