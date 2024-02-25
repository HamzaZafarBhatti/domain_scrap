<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Exception;
use App\Models\Domain;
use Illuminate\Support\Facades\Log;

class DomainScrapJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private $domain, private $year)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $response = Http::withHeaders([
            'referer' => 'https://web.archive.org/',
        ])->get('https://web.archive.org/__wb/sparkline', [
            'output' => 'json',
            'url' => $this->domain,
            'collection' => 'web',
        ]);
        $web = $response->json();
        Log::info('Response from archive:');
        Log::info(json_encode($web));
        try {
            if ($web['first_ts'] == null || $web['last_ts'] == null || $web['years'] == [] || $web['status'] == []) {
                return;
            }
            $first_date = Carbon::parse(strtotime($web['first_ts']))->format('Y');
            $last_date = Carbon::parse(strtotime($web['last_ts']))->format('Y');
        } catch (Exception) {
            return;
        }
        if (($this->year - $first_date <= 0) || ($this->year - $last_date <= 0)) {
            $response = Http::withHeaders([
                'X-RapidAPI-Host' => 'domainr.p.rapidapi.com',
                'X-RapidAPI-Key' => 'ee945fba55msh43c04ba37ae8d39p1e79d0jsn487ddd1f7dad',
            ])->get('https://domainr.p.rapidapi.com/v2/status?mashape-key=d03abf08787645d4a17386782f11b0b7&domain=' . $this->domain);

            if ($response->status() == 200) {
                $data = $response->json();
                Log::info('Response from domainr:');
                Log::info(json_encode($data));
                if (str_contains($data['status'][0]['status'], 'inactive')) {
                    Domain::updateOrCreate(['domain_name' => $data['status'][0]['domain']], ['domain_name' => $data['status'][0]['domain']]);
                }
            }
        }
    }
}
