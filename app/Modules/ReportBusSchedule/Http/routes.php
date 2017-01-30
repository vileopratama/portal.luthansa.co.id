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

Route::group(['prefix' => 'report-bus-schedule','middleware' => ['web','permission','access_read:report-bus-schedule']], function() {
    Route::get('/', ['uses' => 'ReportBusScheduleController@index','middleware' => ['access_read:report-bus-schedule']]);
    Route::get('/export/excel', ['uses' => 'ReportBusScheduleController@export_excel','middleware' => ['access_create:report-bus-schedule']]);
});