<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Url;
use Illuminate\Support\Facades\Queue;
use App\Jobs\GeolocateRequest;
use Illuminate\Support\Facades\Bus;
use Illuminate\Queue\Jobs\Job;

class GeolocationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    function it_geolocates_based_on_ip()
    {
        $this->withoutExceptionHandling();
        Queue::fake();
        $url = factory('App\Url')->create();
        $this->get($url->path);
        Queue::assertPushed(GeolocateRequest::class, function($job) use ($url) {
            return ($job->url->id === $url->id);
        });
    }
}
