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

Route::group(['prefix' => 'report-schedule-order','middleware' => ['web','permission','access_read:report-schedule-order']], function() {
    Route::get('/', ['uses' => 'ReportScheduleOrderController@index','middleware' => ['access_read:report-schedule-order']]);
    Route::get('/export/excel', ['uses' => 'ReportScheduleOrderController@export_excel','middleware' => ['access_create:report-schedule-order']]);
});