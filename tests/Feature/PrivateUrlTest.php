<?php

namespace Tests\Feature;

use App\Url;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PrivateUrlTest extends TestCase
{
	use RefreshDatabase;
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
        $this->withExceptionHandling();
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
    function user_cannot_update_other_users_private_links()
    {
        $this->withExceptionHandling();        
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
        $this->withExceptionHandling();        
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
        $this->withExceptionHandling();        
        $url = factory('App\Url')->create(['user_id' => factory('App\User')->create()->id]);
        $this->get(route('url.stats', ['url' => $url->slug]))->assertStatus(403);
        $this->getJson(route('api.url.stats', ['url' => $url->slug]))->assertStatus(403);
    }

    /**
     * @test
     */
    function members_cannot_see_stats_of_private_url_they_dont_own()
    {
        $this->withExceptionHandling();        
        $this->signIn();
        $url = factory('App\Url')->create(['user_id' => factory('App\User')->create()->id]);
        $this->get(route('url.stats', ['url' => $url->slug]))->assertStatus(403);
        $this->getJson(route('api.url.stats', ['url' => $url->slug]))->assertStatus(403);
    }

    /**
     * @test
     */
    function members_can_see_stats_for_their_urls()
    {
        $this->signIn();
        $url = factory('App\Url')->create(['user_id' => auth()->id()]);
        $this->get(route('url.stats', ['url' => $url->slug]))->assertStatus(200);
        $this->getJson(route('api.url.stats', ['url' => $url->slug]))->assertStatus(200);
    }
}
