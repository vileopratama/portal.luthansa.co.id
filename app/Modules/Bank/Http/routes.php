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

Route::group(['prefix' => 'bank','middleware' => ['web','permission','access_read:bank']], function() {
	Route::get('/', ['uses' => 'BankController@index','middleware' => ['access_read:bank']]);
	Route::get('/create', ['uses' => 'BankController@create','middleware' => ['access_create:bank']]);
	Route::get('/view/{slug}', ['uses' => 'BankController@view','middleware' => ['access_read:bank']]);
	Route::get('/edit/{slug}', ['uses' => 'BankController@edit','middleware' => ['access_update:bank']]);
	Route::get('/do-publish/{slug}', ['uses' => 'BankController@do_publish','middleware' => ['access_update:bank']]);
	Route::post('/do-update', ['uses' => 'BankController@do_update','middleware' => ['access_update:bank']]);
	Route::post('/do-delete', ['uses' => 'BankController@do_delete','middleware' => ['access_delete:bank']]);
});