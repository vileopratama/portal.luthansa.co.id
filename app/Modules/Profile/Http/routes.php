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

Route::group(['prefix' => 'profile','middleware' => ['web','permission','access_read:profile']], function() {
	Route::get('/', ['uses' => 'ProfileController@index','middleware' => ['access_read:profile']]);
	Route::get('/password', ['uses' => 'ProfileController@password','middleware' => ['access_update:profile']]);
	Route::post('/do-update/profile', ['uses' => 'ProfileController@do_update_profile','middleware' => ['access_update:profile']]);
	Route::post('/do-update/password', ['uses' => 'ProfileController@do_update_password','middleware' => ['access_update:profile']]);
});