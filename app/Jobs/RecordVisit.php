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
use Illuminate\Support\Facades\Cache;

class RecordVisit implements ShouldQueue
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
    public function handle(Client $client)
    {
        if ($this->isInCache()) return;
        $country = $this->geolocateRequest($client);
        Cache::put($this->cacheKey(), true, (60 * 24));
        Visit::create([
            'country' => $country,
            'url_id' => $this->url->id
        ]);
    }

    protected function geolocateRequest($client)
    {
        $response = $client->request('GET', 'http://www.geoplugin.net/php.gp?ip=' . $this->ip);
        $data = unserialize($response->getBody());
        return $data['geoplugin_countryName'] ?? null;
    }

    protected function isInCache()
    {
        return Cache::get($this->cacheKey()) !== null;
    }

    protected function cacheKey()
    {
        return $this->url->id . "." . $this->ip;
    }
}
