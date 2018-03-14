<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GithubLoginTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * @test
     */
    function users_can_login_with_github()
    {
        $this->successResponse();
        $this->get('auth/github/callback');
        $this->assertCount(1, User::all());
    }
    
    /**
     * @test
     */
    function it_only_creates_a_user_once()
    {
        $this->successResponse();
        $this->get('auth/github/callback');
        $this->assertCount(1, User::all());
        $this->get('auth/github/callback');
        $this->assertCount(1, User::all());
    }
    
    /**
     * @test
     */
    function it_updates_the_user_if_it_finds_an_existing_email()
    {
        $user = factory('App\User')->create();
        $this->successResponse(['email' => $user->email]);
        $this->get('auth/github/callback');
        $this->assertCount(1, User::all());
        $this->assertEquals($this->sampleUser()['id'], $user->fresh()->provider_id);
    }
    
    protected function successResponse($overrides = [])
    {
        Socialite::shouldReceive('driver->user')->andReturn((object) $this->sampleUser($overrides));
    }
    
    protected function sampleUser($overrides = [])
    {
        return array_merge([
            'email' => 'test@mail.com',
            'nickname' => 'piou',
            'id' => 12356,
        ], $overrides);
    }
}

