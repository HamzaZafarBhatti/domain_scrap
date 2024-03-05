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
use App\Models\JobDone;
use Illuminate\Support\Facades\Log;

class DomainScrapJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private $domain, private $year,private $niche=null,private $sub_niche=null,private $country = null,private $city = null)
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
                    Domain::updateOrCreate(['domain_name' => $data['status'][0]['domain']], ['sub_niche_id'=> $this->sub_niche?->id,'niche_id' => $this->niche?->id,'is_job' => true,'domain_name' => $data['status'][0]['domain']]);
                }
            }
        }
        JobDone::create([
            'is_country' => $this->country ? true : false,
            'is_city' => $this->city ? true : false,
            'is_niche' => $this->niche ? true : false,
            'is_sub_niche' => $this->sub_niche ? true : false,
            'status' => 'Completed',
            'domain' => $this->domain
        ]);
    }
    public function failed(\Throwable $exception)
    {
        JobDone::create([
            'is_country' => $this->country ? true : false,
            'is_city' => $this->city ? true : false,
            'is_niche' => $this->niche ? true : false,
            'is_sub_niche' => $this->sub_niche ? true : false,
            'status' => 'Failed',
            'domain' => $this->domain
        ]);
    }
}
