<?php

namespace Tests\Feature;

use Tests\TestCase;
use GuzzleHttp\Client;
use App\Jobs\CreatedUrl;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUrlJobTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    function it_gets_the_title_of_the_page()
    {
        $body = '<html><head><title>Google</title></head></html>';
        $this->mockGuzzle([[200, [], $body]]);

        $url = factory('App\Url')->create(['url' => "google.com"]);
        CreatedUrl::dispatch($url);
        $this->assertEquals('Google', $url->fresh()->title);   
    }
    
    /**
     * @test
     */
    function it_leaves_the_title_as_null_if_the_link_cannot_be_found()
    {
        $this->mockGuzzle([[404, []]]);
        $url = factory('App\Url')->create();
        CreatedUrl::dispatch($url);
        $this->assertEquals(null, $url->fresh()->title);
    }
}
