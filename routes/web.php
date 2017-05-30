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

Route::group(["prefix" => "website"], function() {

    Route::get('/', 'WebsiteController@index');
    Route::get('/create', 'WebsiteController@create');
    Route::post('/store', 'WebsiteController@store');
    Route::get('/show/{id}', 'WebsiteController@show');

});

Route::get('socket', function() {
    return view('socket');
});