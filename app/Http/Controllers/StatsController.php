<?php

namespace App\Http\Controllers;

use App\Url;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatsController extends Controller
{
	public function __construct()
	{
		$this->middleware('PrivateUrlCheck');
	}

    public function show(Url $url)
    {
    	return view('urls.stats', compact('url'));
    }

    public function getVisits(Url $url)
    {
        switch(request()->query('since')) {
            case 'day': $since = Carbon::now()->subHours(24); break;
            case 'week': $since = Carbon::now()->subWeek(); break;
            case 'month' : $since = Carbon::now()->subMonth(); break;
            case 'year' : $since = Carbon::now()->subYear(); break;
            default : $since = Carbon::now()->subHours(24); break;
        }
        return $url->visits()->where('created_at', '>', $since)->orderBy('created_at', 'desc')->get();
    }
}
