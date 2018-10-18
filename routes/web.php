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

// DASHBOARD
Route::any('/dashboard/loaylty', 'DashboardController@index')->middleware('role:superadministrator|administrator'); 
Route::any('/dashboard/manage/users', 'DashboardController@index')->middleware('role:superadministrator'); 

Route::any('/{vue_capture?}', 'DashboardController@index')->where('vue_capture', '^(dashboard).*$')->middleware('role:administrator|superadministrator|user'); 


Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('privacy', 'Auth\PageController@privacy');
Route::get('tos', 'Auth\PageController@tos');

//oute::prefix('dashboard')->any('/','DashboardController@index');
//Route::any('/dashboard','DashboardController@index');

//Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
