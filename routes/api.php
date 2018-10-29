<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/**
 * Aplication Routes
 */


Route::group(['middleware' => ['auth:api']],function(){

    Route::resource('users', 'UserController')->middleware('role:superadministrator');
    Route::post('users/load','UserController@load')->middleware('role:superadministrator');
    Route::post('users/profile/{id}','UserController@profile');

    Route::resource('customers', 'API\CustomerController')->except([
        'show','edit'
    ])->middleware('role:superadministrator|administrator');
    Route::post('customers/load','API\CustomerController@load')->middleware('role:superadministrator|administrator');
    Route::get('customers/options','API\CustomerController@get_select_options')->middleware('role:superadministrator|administrator');


    Route::apiResources([
        'orders' => 'API\OrderController',
    ],['middleware' => ['role:superadministrator|administrator']]);

    Route::post('orders/load','API\OrderController@load')->middleware('role:superadministrator|administrator');

    Route::resource('rewards', 'API\RewardController')->except([
         'show','edit'
    ])->middleware('role:superadministrator|administrator');
    
    Route::post('rewards/{id}','API\RewardController@update')->middleware('role:superadministrator|administrator');
    Route::get('rewards/options','API\RewardController@get_select_options')->middleware('role:superadministrator|administrator');

    Route::apiResources([
        'cards' => 'API\CardController',
    ],['middleware' => ['role:superadministrator|administrator']]);

});


/**
 * External Api routes
 */

Route::post('mobile/register/customer','Mobile\CustomerController@store');

Route::group(['middleware' => ['auth:customers-api'], 'prefix' => 'mobile'],function(){

    Route::resource('customers', 'Mobile\CustomerController')->except([
        'create','edit','show','destroy','store','update'
    ]);
    Route::put('customers','Mobile\CustomerController@update');

    Route::resource('cards', 'Mobile\CardController')->except([
        'create', 'update','edit'
    ]);

    Route::put('cards/{id}','Mobile\CardController@update');

    Route::resource('rewards', 'Mobile\RewardController')->only([
        'index', 'show'
    ]);

    Route::resource('orders', 'Mobile\OrderController')->only([
        'index', 'show','store','update'
    ]);

});



