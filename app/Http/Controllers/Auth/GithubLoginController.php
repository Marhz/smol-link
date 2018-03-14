<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GithubLoginController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }
    
    public function handleCallback()
    {
        try {
            $socialiteUser =  Socialite::driver('github')->user();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(['flash' => 'There was a problem while logging you with github, please try again']);
        }
        $this->loginUser($socialiteUser);
        return redirect('/dashboard');
    }
    
    protected function loginUser($socialiteUser)
    {
        if ($user = User::whereNull('provider_id')->where('email', $socialiteUser->email)->first()) {
            $user->provider = 'github';
            $user->provider_id = $socialiteUser->id;
            $user->save();
        } else {
            $user = User::firstOrCreate(
                ['provider' => 'github', 'provider_id' => $socialiteUser->id],
                [
                    'email' => $socialiteUser->email,
                    'name' => $socialiteUser->nickname,
                    'provider' => 'github',
                    'provider_id' => $socialiteUser->id
                ]
            );
        }
        Auth::login($user);
    }
}
