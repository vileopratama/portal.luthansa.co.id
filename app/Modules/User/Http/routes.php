<?php

/*
|--------------------------------------------------------------------------
| Module Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for the module.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(['prefix' => 'user','middleware' => ['web','permission','access_read:user']], function() {
	Route::get('/', ['uses'=>'UserController@index','middleware' => ['access_read:user']]);
	Route::get('/create', ['uses'=>'UserController@create','middleware' => ['access_create:user']]);
	Route::get('/view/{slug}', ['uses'=>'UserController@view','middleware'=>['access_read:user']]);
	Route::get('/edit/{slug}', ['uses'=>'UserController@edit','middleware'=>['access_update:user']]);
	Route::get('/reset-password/{slug}', ['uses'=>'UserController@reset_password','middleware'=>['access_update:user']]);
	Route::get('/do-publish/{slug}', ['uses'=>'UserController@do_publish','middleware'=>['access_update:user']]);
	Route::post('/do-update', ['uses'=>'UserController@do_update','middleware'=>['access_update:user']]);
	Route::post('/do-update/password', ['uses'=>'UserController@do_update_password','middleware'=>['access_update:user']]);
	Route::post('/do-delete', ['uses'=>'UserController@do_delete','middleware'=>['access_delete:user']]);
});