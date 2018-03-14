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
            $user = Socialite::driver('github')->user();
        } catch (\Exception $e) {
            return redirect()->back()->with(['flash' => 'There was a problem while logging you with github, please try again']);
        }
        if ($existingUser = User::where('email', $user->email)->first()) {
           $existingUser->provider = 'github';
           $existingUser->provider_id = $user->id;
           $existingUser->save();
           Auth::login($existingUser);
           return redirect('/dashboard'); 
        }
        if ($existingUser = User::where('provider', 'github')->where('provider_id', $user->id)->first()) {
            Auth::login($existingUser);
            return redirect('/dashboard');
        }
        $user = User::create([
            'email' => $user->email,
            'name' => $user->nickname,
            'provider' => 'github',
            'provider_id' => $user->id
        ]);
        Auth::login($user);
        return redirect('/dashboard');
    }
}
