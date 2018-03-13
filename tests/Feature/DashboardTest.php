<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
	use RefreshDatabase;
	
	/**
	 * @test
	 */
	function guests_are_redirected_to_the_login_page()
	{
		$this->withExceptionHandling();
		$this->get('dashboard')
			->assertRedirect('login');
	}
	
	/**
	 * @test
	 */
	function non_confirmed_members_cannot_see_the_dashboard()
	{
		$user = factory('App\User')->create(['confirmation_token' => "notnull"]);
		$this->signIn($user);
		$this->get('dashboard')
			->assertRedirect('/');	
	}
	
	/**
	 * @test
	 */
	function confirmed_members_can_see_the_dashboard()
	{
		$this->signIn();
		$this->get('dashboard')
			->assertStatus(200);	
	}
}
