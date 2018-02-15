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
        $this->assertEquals(0, $url->visits);
        $this->get(route('url.show', ['url' => $url->slug]));
        $this->assertEquals(1, $url->fresh()->visits);
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
}
