<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

    public function show()
    {
    	$user = auth()->user();
    	return view('dashboard.show', compact('user'));
    }

    public function getUrls()
    {
    	return auth()->user()->load(['urls' => function($query) {
    		$query->withCount('visits');
    	}])->urls;
    }
}
