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

Route::group(['prefix' => 'session','middleware' => ['web']], function() {
	Route::get('login', ['uses'=>'SessionController@login','middleware' => ['logged']]);
	Route::get('is_login', ['uses'=>'SessionController@is_login']);
	Route::post('do-login', 'SessionController@do_login');
	Route::get('logout', 'SessionController@logout');
});