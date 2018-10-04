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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('customers', 'CustomerController');
Route::get('data/customers/subsets', 'CustomerController@getDataForIdVector');
Route::put('data/customers/{customer}/update', 'CustomerController@updateDataAfterChangeImageOrCustomer');

Route::apiResource('events', 'EventController');
Route::post('result/detections', 'EventController@sendResultFaceDetection');
Route::post('result/beginner-detections', 'EventController@sendResultBeginnerFaceDetection');
