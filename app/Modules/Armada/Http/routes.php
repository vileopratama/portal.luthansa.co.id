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

Route::group(['prefix' => 'armada','middleware' => ['web','permission','access_read:armada']], function() {
	Route::get('/', ['uses' => 'ArmadaController@index','middleware' => ['access_read:armada']]);
	Route::get('/create', ['uses' =>'ArmadaController@create','middleware' => ['access_create:armada']]);
	Route::get('/view/{slug}', ['uses' =>'ArmadaController@view','middleware' => ['access_read:armada']]);
	Route::get('/edit/{slug}', ['uses' =>'ArmadaController@edit','middleware' => ['access_update:armada']]);
	Route::get('/do-publish/{slug}', ['uses' =>'ArmadaController@do_publish','middleware' => ['access_update:armada']]);
	Route::post('/do-update', ['uses' =>'ArmadaController@do_update','middleware' => ['access_update:armada']]);
	Route::post('/do-delete', ['uses' =>'ArmadaController@do_delete','middleware' => ['access_delete:armada']]);
});