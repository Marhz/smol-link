<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Url;
use App\Jobs\RecordVisit;
use Carbon\Carbon;

class VisitsRecordingTest extends TestCase
{
    use RefreshDatabase;
    
    protected $url;

    public function setUp()
    {
        parent::setUp();
        $this->mockGuzzle();
        $this->url = factory('App\Url')->create();
    }

    /**
     * @test
     */
    function it_increments_the_number_of_visits_when_a_link_is_visited()
    {
        $this->assertEquals(0, $this->url->visits_count);
        RecordVisit::dispatch($this->url, '', '');
        $this->assertEquals(1, $this->url->fresh()->visits_count);
    }

    /**
     * @test
     */
    function it_doesnt_increment_the_visits_twice_for_the_same_ip()
    {
        $this->assertEquals(0, $this->url->visits_count);
        RecordVisit::dispatch($this->url, '127.0.0.1', '');
        $this->assertEquals(1, $this->url->fresh()->visits_count);
        RecordVisit::dispatch($this->url, '127.0.0.1', '');
        $this->assertEquals(1, $this->url->fresh()->visits_count);
    }


    /**
     * @test
     */
    function it_refreshes_the_cache_timer_after_a_day()
    {
        $this->mockCache();

        $this->assertEquals(0, $this->url->visits_count);
        RecordVisit::dispatch($this->url, '', '');
        $this->assertEquals(1, $this->url->fresh()->visits_count);
        RecordVisit::dispatch($this->url, '', '');
        $this->assertEquals(1, $this->url->fresh()->visits_count);
        Carbon::setTestNow(now()->addHours(25));

        RecordVisit::dispatch($this->url, '', '');
        $this->assertEquals(2, $this->url->fresh()->visits_count);
    }

    /**
     * @test
     */
    function it_tries_to_get_a_referrer()
    {
        $url = factory('App\Url')->create();
        RecordVisit::dispatch($this->url, '', 'https://l.facebook.com/');
        $this->assertEquals('Facebook', $this->url->visits()->first()->referrer);
        $url = factory('App\Url')->create();
        RecordVisit::dispatch($url, '', 'https://random-site.com');
        $this->assertNull($url->visits()->first()->referrer);
    }
}
