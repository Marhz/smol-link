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

    protected function mockGuzzle($responses = [])
    {
        app()->bind('GuzzleHttp\Client', function ($app) use ($responses) {
            $mock = [];
            if (count($responses) === 0) {
                $mock = [new Response(200, ['X-Foo' => 'Bar'])]; 
            }
            foreach($responses as $status => $content) {
                $mock[] = new Response($status, $content);
            }
            $mock = new MockHandler($mock);
            $this->withoutExceptionHandling();
            $handler = HandlerStack::create($mock);
            return new Client(['handler' => $handler]);
        });
    }

    protected function signIn($user = null)
    {
    	$user = $user ?: factory('App\User')->create();
		$this->actingAs($user);
		return $this;
    }
}
