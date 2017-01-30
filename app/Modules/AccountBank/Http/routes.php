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

Route::group(['prefix' => 'account-bank','middleware' => ['web','permission','access_read:account-bank']], function() {
	Route::get('/', ['uses' => 'AccountBankController@index','middleware' => ['access_read:account-bank']]);
	Route::get('/create', ['uses' =>'AccountBankController@create','middleware' => ['access_create:account-bank']]);
	Route::get('/view/{slug}', ['uses' =>'AccountBankController@view','middleware' => ['access_read:account-bank']]);
	Route::get('/edit/{slug}', ['uses' =>'AccountBankController@edit','middleware' => ['access_update:account-bank']]);
	Route::get('/do-publish/{slug}', ['uses' =>'AccountBankController@do_publish','middleware' => ['access_update:account-bank']]);
	Route::post('/do-update', ['uses' =>'AccountBankController@do_update','middleware' => ['access_update:account-bank']]);
	Route::post('/do-delete', ['uses' =>'AccountBankController@do_delete','middleware' => ['access_delete:account-bank']]);
});