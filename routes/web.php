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
    return view('welcome');
});

Auth::routes();

Route::get('/dashboard', 'DashboardController@show')->name('dashboard.show');
Route::get('/dashboard/links/{id}', 'DashboardController@show');
Route::get('/{url}/stats', 'StatsController@show')->name('url.stats');
Route::post('/url/store', 'UrlController@store')->name('url.store');
Route::get('/{url}', 'UrlController@show')->name('url.show');

Route::get('/register/confirm', 'Auth\EmailConfirmationController@index');

Route::get('/api/{url}/visits', 'StatsController@getVisits')->name('api.url.stats');
Route::put('/api/{url}/update', 'UrlController@update')->name('url.update');
Route::get('/api/urls', 'DashboardController@getUrls');

Route::get('auth/github', 'Auth\GithubLoginController@redirectToProvider');
Route::get('auth/github/callback', 'Auth\GithubLoginController@handleCallback');
