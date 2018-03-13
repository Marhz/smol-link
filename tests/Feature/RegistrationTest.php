<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Mail\EmailConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * @test
     */
    function a_confirmation_email_is_sent_on_registration()
    {
        Mail::fake();
        event(new Registered(factory('App\User')->create()));    
        Mail::assertSent(EmailConfirmation::class);
    }
    
    /**
     * @test
     */
    function a_user_can_confirm_his_email()
    {
        Mail::fake();
        $this->post('register', [
            'name' => 'test',
            'email' => 'test@mail.com',
            'password' => 'azerty',
            'password_confirmation' => 'azerty'
        ]); 
        $user = User::first();
        $this->assertFalse($user->isConfirmed());
        $this->assertNotNull($user->confirmation_token);

        $res = $this->get('/register/confirm?token=' . $user->confirmation_token);
        $this->assertTrue($user->fresh()->isConfirmed());
        $res->assertRedirect('/dashboard')->assertSessionHas('flash');
    }
}
