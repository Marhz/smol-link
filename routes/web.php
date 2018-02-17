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
	// $u = App\Url::all()[1];
	// for ($i = 0; $i < 50; $i++) {
	// 	$date = carbon\carbon::now()->subHours(rand(0, 24));
	// 	$u->visits()->create(['created_at' => $date]);
	// }
	// for ($i = 0; $i < 50; $i++) {
	// 	$date = carbon\carbon::now()->subDays(rand(3, 6));
	// 	$u->visits()->create(['created_at' => $date]);
	// }
	// for ($i = 0; $i < 100; $i++) {
	// 	$date = carbon\carbon::now()->subWeeks(rand(3, 20));
	// 	$u->visits()->create(['created_at' => $date]);
	// }
    return view('welcome');
});

Auth::routes();

Route::get('/dashboard', 'DashboardController@show')->name('dashboard.show');
Route::get('/dashboard/links/{id}', 'DashboardController@show');
Route::get('/{url}/stats', 'StatsController@show')->name('url.stats');
Route::post('/url/store', 'UrlController@store')->name('url.store');
Route::get('/{url}', 'UrlController@show')->name('url.show');

Route::get('/api/{url}/visits', 'StatsController@getVisits')->name('api.url.stats');
Route::put('/api/{url}/update', 'UrlController@update')->name('url.update');
