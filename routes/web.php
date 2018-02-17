<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	// $u = App\Url::first();
	// for ($i = 0; $i < 100; $i++) {
	// 	$date = carbon\carbon::now()->subdays(rand(0, 6));
	// 	$u->visits()->create(['created_at' => $date]);
	// }
	// for ($i = 0; $i < 100; $i++) {
	// 	$date = carbon\carbon::now()->subWeeks(rand(3, 20));
	// 	$u->visits()->create(['created_at' => $date]);
	// }
	// echo $u->visits_count;
	// echo $u->visits_count;
	// echo $u->visits_count;
    return view('welcome');
});

Auth::routes();

Route::get('/{url}/stats', 'StatsController@show')->name('url.stats');
Route::post('url/store', 'UrlController@store')->name('url.store');
Route::get('/profile', 'ProfileController@show')->name('profile.show');
Route::get('/{url}', 'UrlController@show')->name('url.show');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/api/{url}/visits', 'StatsController@getVisits')->name('api.url.stats');
Route::put('/api/{url}/update', 'UrlController@update')->name('url.update');
