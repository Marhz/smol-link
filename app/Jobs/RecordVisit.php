<?php

namespace App\Jobs;

use App\Url;
use App\Visit;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RecordVisit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $url;
    public $ip;
    public $tries = 3;
    public $referrer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Url $url, $ip, $referrer)
    {
        $this->url = $url;
        $this->ip = $ip;
        $this->referrer = $referrer;
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
            'url_id' => $this->url->id,
            'referrer' => $this->getReferrer()
        ]);
    }

    protected function geolocateRequest($client)
    {
        $response = $client->request('GET', 'http://www.geoplugin.net/php.gp?ip=' . $this->ip);
        $data = unserialize($response->getBody());
        $country = $data['geoplugin_countryName'] ?? null;
        return $country ? : null;
    }

    protected function getReferrer()
    {
        $referrers = config('custom.socialMediaReferrers');
        foreach ($referrers as $socialMedia => $urls) {
            foreach($urls as $url) {
                if (preg_match('#^' . $url . '.*#', $this->referrer)) {
                    return $socialMedia;
                }
            }
        }
        return null;
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
