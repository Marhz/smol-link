<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function show()
    {
    	$user = auth()->user()->load(['urls' => function($query) {
    		$query->withCount('visits');
    	}]);
    	return view('profile.show', compact('user'));
    }
}
