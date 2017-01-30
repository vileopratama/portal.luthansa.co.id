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

Route::group(['prefix' => 'armada-category','middleware' => ['web','permission','access_read:armada-category']], function() {
	Route::get('/', ['uses' =>'ArmadaCategoryController@index','middleware' => ['access_read:armada-category']]);
	Route::get('/create', ['uses' =>'ArmadaCategoryController@create','middleware' => ['access_create:armada-category']]);
	Route::get('/view/{slug}', ['uses' =>'ArmadaCategoryController@view','middleware' => ['access_read:armada-category']]);
	Route::get('/edit/{slug}', ['uses' =>'ArmadaCategoryController@edit','middleware' => ['access_update:armada-category']]);
	Route::get('/do-publish/{slug}', ['uses' =>'ArmadaCategoryController@do_publish','middleware' => ['access_update:armada-category']]);
	Route::post('/do-update', ['uses' =>'ArmadaCategoryController@do_update','middleware' => ['access_update:armada-category']]);
	Route::post('/do-delete', ['uses' =>'ArmadaCategoryController@do_delete','middleware' => ['access_delete:armada-category']]);
});