<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use App\Console\Commands\CleanUsers;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CleanUsersCommandTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp()
    {
       parent::setUp(); 
       $this->command = new CleanUsers();
    }
    /**
     * @test
     */
    function it_cleans_uncomfimed_users_after_a_given_time()
    {
        $user = factory('App\User')->create(['confirmation_token' => 'notnull']);
        Carbon::setTestNow(Carbon::now()->addDays(42));
        $this->command->handle();
        $this->assertCount(0, User::all());
    }
    
    /**
     * @test
     */
    function it_doesnt_delete_confirmed_users()
    {
        $user = factory('App\User')->create();
        $oldUser = factory('App\User')->create(['created_at' => Carbon::now()->subMonth(2)]);
        $this->command->handle();
        $this->assertCount(2, User::all());
    }
    
    /**
     * @test
     */
    function it_doesnt_delete_new_uncomfirme_users()
    {
        $user = factory('App\User')->create(['confirmation_token' => 'notnull']);
        $this->command->handle();
        $this->assertCount(1, User::all());
    }
}
