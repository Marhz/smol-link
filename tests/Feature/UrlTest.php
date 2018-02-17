<?php

namespace Tests\Feature;

use App\Url;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UrlTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    function it_creates_a_minified_url_for_an_url()
    {
        $this->withoutExceptionHandling();
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
        $this->withoutExceptionHandling();
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
    function it_increments_the_number_of_visits_when_a_link_is_visited()
    {
        $url = factory('App\Url')->create();
        $this->assertEquals(0, $url->visits_count);
        $res = $this->get(route('url.show', ['url' => $url->slug]));
        $this->assertEquals(1, $url->fresh()->visits_count);
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
    function it_gets_visits_for_a_given_timeframe()
    {
        // $this->withoutExceptionHandling();
        $url = factory('App\Url')->create();
        for ($i = 0; $i < 100; $i++) {
            $date = \Carbon\Carbon::now()->subDays(rand(0, 6));
            $url->visits()->create(['created_at' => $date]);
        }
        for ($i = 0; $i < 100; $i++) {
            $date = \Carbon\Carbon::now()->subWeeks(rand(3, 50));
            $url->visits()->create(['created_at' => $date]);
        }
        $res = $this->getJson('api/' . $url->slug . '/visits?since=week');
        $this->assertCount(100, $res->json());
    }

    /**
     * @test
     */
    function registered_users_have_private_links()
    {
        $this->signIn();
        $url = factory('App\Url')->create()->url;
        $this->post('/url/store', ['url' => $url]);
        $this->assertEquals(2, Url::count());
    }

    /**
     * @test
     */
    function private_links_can_have_labels()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $url = factory('App\Url')->create(['user_id' => auth()->id()]);
        $this->putJson(route('url.update', ['url' => $url->slug]),
            array_merge($url->toArray(), ['label' => 'a label'])
        );
        $this->assertEquals('a label', $url->fresh()->label);
    }

    /**
     * @test
     */
    function private_links_can_have_personalized_slugs()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $url = factory('App\Url')->create(['user_id' => auth()->id()]);
        $this->putJson(route('url.update', ['url' => $url->slug]),
            array_merge($url->toArray(), ['slug' => 'h3ll0'])
        );
        $this->assertEquals('h3ll0', $url->fresh()->slug);
    }

    /**
     * @test
     */
    function personalized_slugs_dont_collide_with_existing_slugs()
    {
        $this->signIn();
        $url = factory('App\Url')->create();
        $userUrl = factory('App\Url')->create(['user_id' => auth()->id()]);
        $this->putJson(route('url.update', ['url' => $userUrl->slug]),
            array_merge($url->toArray(), ['slug' => $url->slug])
        )->assertStatus(422);
    }

    /**
     * @test
     */
    function user_cannot_update_other_users_links()
    {
        $this->signIn();
        $url = factory('App\Url')->create(['user_id' => factory('App\User')->create()->id]);
        $this->putJson(route('url.update', ['url' => $url->slug]),
            $url->toArray()
        )->assertStatus(403);
    }

    /**
     * @test
     */
    function guest_cannot_update_urls()
    {
        $url = factory('App\Url')->create(['user_id' => factory('App\User')->create()->id]);
        $this->putJson(route('url.update', ['url' => $url->slug]),
            $url->toArray()
        )->assertStatus(401);
    }

    /**
     * @test
     */
    function guest_cannot_see_private_url_stats()
    {
        $url = factory('App\Url')->create(['user_id' => factory('App\User')->create()->id]);
        $this->get(route('url.stats', ['url' => $url->slug]))->assertStatus(403);
        $this->getJson(route('api.url.stats', ['url' => $url->slug]))->assertStatus(403);
    }

    /**
     * @test
     */
    function members_cannot_see_stats_of_private_url_they_dont_own()
    {
        $this->signIn();
        $url = factory('App\Url')->create(['user_id' => factory('App\User')->create()->id]);
        $this->get(route('url.stats', ['url' => $url->slug]))->assertStatus(403);
        $this->getJson(route('api.url.stats', ['url' => $url->slug]))->assertStatus(403);
    }

    /**
     * @test
     */
    function members_can_ssee_tats_for_their_urls()
    {
        $this->signIn();
        $url = factory('App\Url')->create(['user_id' => auth()->id()]);
        $this->get(route('url.stats', ['url' => $url->slug]))->assertStatus(200);
        $this->getJson(route('api.url.stats', ['url' => $url->slug]))->assertStatus(200);
    }
}
