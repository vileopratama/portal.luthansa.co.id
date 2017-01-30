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

Route::group(['prefix' => 'setting','middleware' => ['web','permission','access_read:setting']], function() {
	Route::get('/', ['uses' => 'SettingController@index','middleware' => ['access_read:setting']]);
	Route::post('/do-update', ['uses' => 'SettingController@do_update','middleware' => ['access_update:setting']]);
});