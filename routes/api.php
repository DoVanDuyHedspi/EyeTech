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


Route::group(['prefix' => 'v1'], function() {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('store/register', 'Api\StoreController@store');
        Route::post('users/register', 'Api\UserController@store');
        Route::post('users/login', 'Api\UserController@login');

        Route::group(['prefix' => 'users', 'middleware' => 'auth:api'], function () {
            Route::get('logout', 'Api\UserController@logout');
            Route::post('detail', 'Api\UserController@detail');
            Route::get('customers/vector-id', 'Api\CustomerController@getDataForIdVector');
            Route::apiResource('customers', 'Api\CustomerController');

            Route::apiResource('events', 'Api\EventController');

            Route::apiResource('detections', 'Api\DetectionController')->only([
                'store'
            ]);
            Route::apiResource('', 'Api\UserController')->only([
                'show', 'update'
            ]);

        });
    });
});
