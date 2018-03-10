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

class CreatedUrl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $url;
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Url $url)
    {
        $this->url = $url;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Client $client)
    {
        $title = $this->getTitle($client);
        if ($title !== null)
            $this->url->update(['title' => $title]);
    }

    public function getTitle($client)
    {
        try {
            $response = $client->request('GET', $this->url->url);
        } catch (\Exception $e) {
            return null;
        }
        $html = $response->getBody()->getContents();
        preg_match("/<title>(.*)<\/title>/", $html, $m);
        return $m ? $m[1] : null;
    }
}
