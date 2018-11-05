<?php

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

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
    Route::post('users/register', 'Api\UserController@store');
    Route::post('users/login', 'Api\UserController@login');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('users/logout', 'Api\UserController@logout');

        Route::group(['prefix' => 'users', 'middleware' => 'role:detection'], function () {
            Route::get('stores-id', 'Api\Admin\StoreController@getStoreID');
            Route::post('customers/vector-id', 'Api\CustomerController@getDataForIdVector');
            Route::apiResource('detections', 'Api\DetectionController')->only([
                'store'
            ]);
        });

        Route::group(['prefix' => 'admin', 'middleware' => 'role:api-admin'], function () {
            Route::apiResource('stores', 'Api\Admin\StoreController');
        });
    });
});
