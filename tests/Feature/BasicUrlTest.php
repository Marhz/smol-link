<?php

namespace Tests\Feature;

use App\Url;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\RecordVisit;
use Illuminate\Support\Facades\Bus;
use App\Jobs\CreatedUrl;

class BasicUrlTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp()
    {
        parent::setUp();
        Queue::fake();
    }
    /**
     * @test
     */
    function it_creates_a_minified_url_for_an_url()
    {
        $url = "example.com";
        $response = $this->post('/url/store', ['url' => $url]);
        $response->assertStatus(201);
        $this->assertEquals(1, Url::count());
        $this->assertEquals($url, Url::first()->url);
    }

    /**
     * @test
     */
    function it_doesnt_create_a_new_minified_url_if_the_url_already_exists()
    {
        $url = "example.com";
        $this->post('/url/store', ['url' => $url]);
        $this->post('/url/store', ['url' => $url]);
        $this->post('/url/store', ['url' => $url. '/']);
        $this->post('/url/store', ['url' => "http://" . $url]);
        $this->post('/url/store', ['url' => "https://" . $url]);
        $this->post('/url/store', ['url' => "http://www." . $url]);
        $this->post('/url/store', ['url' => "https://www." . $url]);
        $this->post('/url/store', ['url' => "www." . $url]);
        $this->assertEquals(1, Url::count());
    }

    /**
     * @test
     */
    function it_trims_trailling_slashes()
    {
        $url = "example.com/";
        $this->post(route('url.store', ['url' => $url]));
        $generatedUrl = Url::first();
        $this->assertEquals("example.com", $generatedUrl->url);
    }

    /**
     * @test
     */
    function it_pushed_the_recording_event_in_the_queue()
    {
        $url = factory('App\Url')->create();
        $this->get($url->path);
        
        Queue::assertPushed(RecordVisit::class, function ($job) use ($url) {
            return ($job->url->id === $url->id);
        });
    }
    
    /**
     * @test
     */
    function it_pushes_the_created_url_event()
    {
        $url = "example.com/";
        $this->post(route('url.store', ['url' => $url]));
        $url = Url::first();
        Queue::assertPushed(CreatedUrl::class, function ($job) use ($url) {
            return ($job->url->id === $url->id);
        });
    }
    
    /**
     * @test
     */
    // function it_gets_visits_for_a_given_timeframe()
    // {
    //     // $this->withoutExceptionHandling();
    //     $url = factory('App\Url')->create();
    //     for ($i = 0; $i < 100; $i++) {
    //         $date = \Carbon\Carbon::now()->subDays(rand(0, 6));
    //         $url->visits()->create(['created_at' => $date]);
    //     }
    //     for ($i = 0; $i < 100; $i++) {
    //         $date = \Carbon\Carbon::now()->subWeeks(rand(3, 50));
    //         $url->visits()->create(['created_at' => $date]);
    //     }
    //     $res = $this->getJson('api/' . $url->slug . '/visits?since=week');
    //     $this->assertCount(100, $res->json());
    // }

}
