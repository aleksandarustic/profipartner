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
Route::group(['middleware' => ['auth:api']],function(){

    Route::resource('users', 'UserController')->middleware('role:superadministrator');
    Route::post('users/load','UserController@load')->middleware('role:superadministrator');
    Route::post('users/profile/{id}','UserController@profile');

    Route::post('customers/load','API\CustomerController@load')->middleware('role:superadministrator|administrator');
    Route::post('orders/load','API\OrderController@load')->middleware('role:superadministrator|administrator');
    Route::get('customers/options','API\CustomerController@get_select_options')->middleware('role:superadministrator|administrator');
    Route::get('rewards/options','API\RewardController@get_select_options')->middleware('role:superadministrator|administrator');

    Route::apiResources([
        'rewards' => 'API\RewardController',
    ],['middleware' => ['role:superadministrator|administrator']]);

    Route::post('rewards/{id}','API\RewardController@update')->middleware('role:superadministrator|administrator');

});

Route::group(['middleware' => []],function(){

    Route::apiResources([
        'cards' => 'API\CardController',
    ]);

    Route::apiResources([
        'customers' => 'API\CustomerController',
    ]);

    Route::apiResources([
        'orders' => 'API\OrderController',
    ]);

});

Route::post('register/user','API\AuthCustomerController@register');


