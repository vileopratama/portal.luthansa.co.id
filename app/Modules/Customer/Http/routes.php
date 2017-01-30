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

Route::group(['prefix' => 'customer','middleware' => ['web','permission','access_read:customer']], function() {
	Route::get('/', ['uses' => 'CustomerController@index','middleware' => ['access_read:customer']]);
	Route::get('/create', ['uses' => 'CustomerController@create','middleware' => ['access_create:customer']]);
	Route::get('/view/{slug}', ['uses' => 'CustomerController@view','middleware' => ['access_read:customer']]);
	Route::get('/edit/{slug}', ['uses' => 'CustomerController@edit','middleware' => ['access_update:customer']]);
	Route::get('/do-publish/{slug}', ['uses' => 'CustomerController@do_publish','middleware' => ['access_update:customer']]);
	Route::post('/do-update', ['uses' => 'CustomerController@do_update','middleware' => ['access_update:customer']]);
	Route::post('/do-delete', ['uses' => 'CustomerController@do_delete','middleware' => ['access_delete:customer']]);
	Route::get('/opportunity', ['uses' => 'CustomerController@opportunity','middleware' => ['access_read:customer']]);
	Route::get('/opportunity/view/{slug}', ['uses' => 'CustomerController@view_opportunity','middleware' => ['access_read:customer']]);
	Route::post('/opportunity/set-order', ['uses' => 'CustomerController@set_order_opportunity','middleware' => ['access_update:customer']]);
});