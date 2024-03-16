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
    private $jobdone = null;
    public $timeout = 0;
    public function __construct(private $domain_tlds, private $location, private $keywords, private $additional_keyword, private $year, private $niche = null, private $sub_niche = null, private $country = null, private $city = null, private $city_name, private $country_name, private $niche_name, private $sub_niche_name)
    {
        $domain_tlds = $this->domain_tlds;
        if(count($domain_tlds) > 1){
            $domain_tlds = \implode(',', $domain_tlds);
        }
        else{
            $domain_tlds = $domain_tlds[0];
        }
        $this->jobdone = JobDone::create([
            'is_country' => $this->country ? true : false,
            'is_city' => $this->city ? true : false,
            'is_niche' => $this->niche ? true : false,
            'is_sub_niche' => $this->sub_niche ? true : false,
            'city_name' => $this->city_name,
            'country_name' => $this->country_name,
            'niche_name' => $this->niche_name,
            'sub_niche_name' => $this->sub_niche_name,
            'domain_tlds' => $domain_tlds,
            'status' => 'Pending',
            'progress' => 0
        ]);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $totalSteps = 0; // Determine total steps. Adjust based on your logic.
        $currentStep = 0;
        $location = $this->location;
        $keywords = $this->keywords;
        $additional_keyword = $this->additional_keyword;
        $domain_tlds = $this->domain_tlds;

        foreach ($domain_tlds as $tld) {
            if (count($location) > 0) {
                $totalSteps = count($location) * count($keywords) * count($domain_tlds);


                foreach ($location as $p_index => $loc) {
                    foreach ($keywords as $index => $key) {
                        $keyword = str_replace(' ', '', $key);
                        $loc_name = str_replace(' ', '', $loc);
                        $domain =  \strtolower($keyword) . $additional_keyword . \strtolower($loc_name) . $tld;
                        // $delay = now()->addMinutes(2 * $p_index + $index);
                        sleep(2);
                        $this->processDomain($domain);
                        $currentStep++;
                        $progress = ($currentStep / $totalSteps) * 100;
                        $this->jobdone->update(['progress' => $progress]);
                    }
                }
            } else {
                $totalSteps = count($keywords) * count($domain_tlds);
                foreach ($keywords as $index => $key) {
                    $keyword = str_replace(' ', '', $key);
                    $domain =  \strtolower($keyword) . $additional_keyword . $tld;
                    // $delay = now()->addMinutes(2 * $index);
                    sleep(2);
                    $this->processDomain($domain);
                    $currentStep++;
                    $progress = ($currentStep / $totalSteps) * 100;
                    $this->jobdone->update(['progress' => $progress]);
                }
            }
        }
        $this->jobdone->update(['progress' => 100]);
        $this->jobdone->update(['status' => 'Completed']);
    }
    public function failed(\Throwable $exception)
    {
        $this->jobdone->update(['status' => 'Failed', 'progress' => 100]);
    }
    public function processDomain($domain)
    {
        try {
            $response = Http::withHeaders([
                'referer' => 'https://web.archive.org/',
            ])->get('https://web.archive.org/__wb/sparkline', [
                'output' => 'json',
                'url' => $domain,
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
                ])->get('https://domainr.p.rapidapi.com/v2/status?mashape-key=d03abf08787645d4a17386782f11b0b7&domain=' . $domain);

                if ($response->status() == 200) {
                    $data = $response->json();
                    Log::info('Response from domainr:');
                    Log::info(json_encode($data));
                    if (str_contains($data['status'][0]['status'], 'inactive')) {
                        Domain::updateOrCreate(['domain_name' => $data['status'][0]['domain']], ['sub_niche_id' => $this->sub_niche?->id, 'niche_id' => $this->niche?->id, 'is_job' => true, 'domain_name' => $data['status'][0]['domain']]);
                    }
                }
            }
        } catch (Exception $e) {
            Log::info('failed by job');
            Log::info(\json_encode($e));
            \sleep(2);
            return;
        }
    }
}
