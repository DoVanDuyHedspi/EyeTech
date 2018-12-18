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
    Route::get('list-stores-id', 'Api\Admin\StoreController@getStoreID');
    Route::get('list-stores-client', 'Api\Admin\StoreController@getStoreForClient');
    Route::post('list-branches-id', 'Api\Admin\BranchController@getBranchID');
    Route::post('list-branches-client', 'Api\Admin\BranchController@getBranchForClient');
    Route::post('events-format', 'Api\EventController@formatEventForClient');
    Route::post('quick-events-format', 'Api\EventController@formatQuickEventForClient');
    Route::post('users/client-login', 'Api\UserController@clientLogin');
    Route::apiResource('cameras', 'Api\Admin\CameraController')->only([
        'index', 'store', 'destroy', 'update'
    ]);
    Route::apiResource('user-types', 'Api\Admin\UserTypeController')->only([
        'index',
    ]);
    Route::get('customers/number-visted', 'Api\CustomerController@getNumberVistedBranch');
    Route::get('customers/{customer}', 'Api\CustomerController@show');
    Route::patch('customers/{customer}', 'Api\CustomerController@update');
    Route::delete('customers/{customer}', 'Api\CustomerController@destroy');
    Route::post('feedbacks/format', 'Api\FeedbackController@formatFeedbacks');
    Route::apiResource('feedbacks', 'Api\FeedbackController');
    Route::delete('galleries/image/destroy', 'Api\GalleryController@removeImage');
    Route::post('galleries/image/insert', 'Api\GalleryController@insertImage');
    Route::post('galleries/image/test', 'Api\GalleryController@testUpload');
    Route::apiResource('galleries', 'Api\GalleryController');

    Route::group(['middleware' => 'auth:api'], function () {

        Route::get('users/logout', 'Api\UserController@logout');

        Route::group(['prefix' => 'users', 'middleware' => 'role:detection'], function () {
            Route::post('customers/vector-id', 'Api\CustomerController@getDataForIdVector');
            Route::apiResource('detections', 'Api\DetectionController')->only([
                'store'
            ]);
        });

        Route::group(['prefix' => 'admin', 'middleware' => 'role:api-admin'], function () {
            Route::apiResource('stores', 'Api\Admin\StoreController');
        });

        Route::apiResource('users', 'Api\UserController')->only([
            'show'
        ]);
    });
});
