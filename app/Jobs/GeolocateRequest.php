<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Url;
use GuzzleHttp\Client;
use App\Visit;

class GeolocateRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $url;
    public $ip;
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Url $url, $ip)
    {
        $this->url = $url;
        $this->ip = $ip;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // var_dump('helloeeeee');
        $client = new Client();
        $this->ip = "85.169.97.45";
        $response = $client->request('GET', 'http://www.geoplugin.net/php.gp?ip=' . $this->ip);
        $data = unserialize($response->getBody());
        Visit::create([
            'country' => $data['geoplugin_countryName'] ?? null,
            'url_id' => $this->url->id
        ]);
    }
}
