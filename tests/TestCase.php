<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    protected function mockGuzzle($responses = [])
    {
        app()->bind('GuzzleHttp\Client', function ($app) use ($responses) {
            $mock = [];
            if (count($responses) === 0) {
                $mock = [new Response(200, ['X-Foo' => 'Bar'])]; 
            }
            foreach($responses as $response) {
                $mock[] = new Response($response[0], $response[1] ?? [], $response[2] ?? null);
            }
            $mock = new MockHandler($mock);
            $this->withoutExceptionHandling();
            $handler = HandlerStack::create($mock);
            return new Client(['handler' => $handler]);
        });
    }

    protected function mockCache()
    {
        app()->bind('cache', function ($app) { 
            return new class {
                protected $cache = [];

                public function put($key, $value, $time)
                {
                    $time = now()->addMinutes($time);
                    $this->cache[$key] = [$value, $time];
                }

                public function get($key)
                {
                    $cache = array_filter($this->cache, function ($item) {
                        return now()->lt($item[1]);
                    });
                    return $cache[$key] ?? null;
                }
            };
        });
    }

    protected function signIn($user = null)
    {
    	$user = $user ?: factory('App\User')->create();
		$this->actingAs($user);
		return $this;
    }
}
