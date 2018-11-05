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

//Route::get('/customers/create', 'TestController@create');
//Route::get('/customers', 'TestController@index');

Route::resource('test', 'TestController');

Route::get('base64-encode', 'TestController@test_base64');
Route::get('base64-decode', 'TestController@test_decode');
