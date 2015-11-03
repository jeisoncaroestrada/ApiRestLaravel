<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::post('login',"UserController@login");
Route::post('users',"UserController@create");
Route::get('session',"UserController@session");
Route::get('logout',"UserController@logout");

Route::get('videos',"VideoController@index");
Route::get('videos/{id}','VideoController@show');
Route::post('videos','VideoController@store');
Route::put('videos/{id}','VideoController@update');
Route::delete('videos/{id}','VideoController@destroy');
