<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Url;
use Illuminate\Support\Facades\Queue;
use App\Jobs\RecordVisit;
use Carbon\Carbon;

class VisitsRecordingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    function it_pushed_the_recording_event_in_the_queue()
    {
        $this->withoutExceptionHandling();
        Queue::fake();
        $url = factory('App\Url')->create();
        $this->get($url->path);
        Queue::assertPushed(RecordVisit::class, function($job) use ($url) {
            return ($job->url->id === $url->id);
        });
    }

    /**
     * @test
     */
    function it_increments_the_number_of_visits_when_a_link_is_visited()
    {
        $this->mockGuzzle();
        $url = factory('App\Url')->create();
        $this->assertEquals(0, $url->visits_count);
        $this->get($url->path);
        $this->assertEquals(1, $url->fresh()->visits_count);
    }

    /**
     * @test
     */
    function it_doesnt_increment_the_visits_twice_for_the_same_ip()
    {
        $this->mockGuzzle();
        $url = factory('App\Url')->create();
        $this->assertEquals(0, $url->visits_count);
        $res = $this->get($url->path);
        $this->assertEquals(1, $url->fresh()->visits_count);
        $res = $this->get($url->path);
        $this->assertEquals(1, $url->fresh()->visits_count);
    }


    /**
     * @test
     */
    function it_refreshes_the_cache_timer_after_a_day()
    {
        $this->mockCache();
        $this->mockGuzzle();

        $url = factory('App\Url')->create();
        $this->assertEquals(0, $url->visits_count);
        $res = $this->get($url->path);
        $this->assertEquals(1, $url->fresh()->visits_count);
        $res = $this->get($url->path);
        $this->assertEquals(1, $url->fresh()->visits_count);
        Carbon::setTestNow(now()->addHours(25));

        $this->get($url->path);
        $this->assertEquals(2, $url->fresh()->visits_count);
    }
}
