<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function()  //, 'middleware' => 'auth:api'
{
    Route::get('/stations', [ 'as'=>'v1.stations','uses'=>'API\AlertSystemController@getStations']);

    Route::get('/station/{id}', [ 'as'=>'v1.station','uses'=>'API\AlertSystemController@getStation']);

    Route::get('/nets', [ 'as'=>'v1.nets','uses'=>'API\AlertSystemController@getNets']);

});