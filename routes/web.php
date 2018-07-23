<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('test');
});

Route::group(['prefix' => 'test','name' => 'test'], function()
{
    Route::get('index', [ 'as'=>'test.index','uses'=>'AlertSystem\testController@index']);
    //Route::get('index', [ 'as'=>'test.index','uses'=>'API\AlertSystemController@getStations']);

});
