<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmailConfirmationController extends Controller
{
    public function index()
    {
        $token = request('token');
        $user = User::where('confirmation_token', $token)
            ->firstOrFail()
            ->update(['confirmation_token' => null]); 
        return redirect('/dashboard')->with(['flash' => 'Email confirmed successfully']);
    }
}
