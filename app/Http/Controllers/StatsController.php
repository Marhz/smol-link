<?php

namespace App\Http\Controllers;

use App\Url;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function show(Url $url)
    {
    	return view('urls.stats', compact('url'));
    }

    public function getVisits(Url $url)
    {
    	switch(request()->query('since')) {
    		case 'day': $since = Carbon::now()->subDays(2); break;
    		case 'week': $since = Carbon::now()->subWeek(); break;
    		case 'month' : $since = Carbon::now()->subMonth(); break;
    		case 'year' : $since = Carbon::now()->subYear(); break;
    		default : $since = Carbon::now()->subWeek(); break;
    	}
    	$visits = $url->load(['visits' => function ($query) use ($since) {
    		$query->where('created_at', '>', $since);
    	}])->visits;
    	return $visits;
    }
}
