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
    Route::get('customers/vector-id', 'Api\CustomerController@getDataForIdVector');
    Route::apiResource('customers', 'Api\CustomerController');

    Route::apiResource('events', 'Api\EventController');

    Route::apiResource('detections', 'Api\DetectionController')->only([
        'store'
    ]);
});
