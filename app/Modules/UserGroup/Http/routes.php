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

Route::group(['prefix' => 'user-group','middleware' => ['web','permission','access_read:user-group']], function() {
	Route::get('/', ['uses' =>'UserGroupController@index','middleware' => ['access_read:user-group']]);
	Route::get('/create', ['uses' =>'UserGroupController@create','middleware' => ['access_create:user-group']]);
	Route::get('/view/{slug}', ['uses' =>'UserGroupController@view','middleware' => ['access_read:user-group']]);
	Route::get('/edit/{slug}', ['uses' =>'UserGroupController@edit','middleware' => ['access_update:user-group']]);
	Route::get('/do-publish/{slug}', ['uses' =>'UserGroupController@do_publish','middleware' => ['access_update:user-group']]);
	Route::post('/do-update', ['uses' =>'UserGroupController@do_update','middleware' => ['access_update:user-group']]);
	Route::post('/do-delete', ['uses' =>'UserGroupController@do_delete','middleware' => ['access_delete:user-group']]);
});