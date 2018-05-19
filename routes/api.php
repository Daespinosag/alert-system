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

Route::group(['prefix' => 'v1'], function(){ //, 'middleware' => 'auth:api'
    /*
    |-------------------------------------------------------------------------------
    | Get All Cafes
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/cafes
    | Controller:     API\CafesController@getCafes
    | Method:         GET
    | Description:    Gets all of the cafes in the application
    */
    Route::get('/stations', function (){
        $element = [];
        array_push($element,['id' => 1, 'name' => 'primera estacion']);
        array_push($element,['id' => 2, 'name' => 'segunda estacion']);

        return $element;
    });

    /*
    |-------------------------------------------------------------------------------
    | Get An Individual Cafe
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/cafes/{id}
    | Controller:     API\CafesController@getCafe
    | Method:         GET
    | Description:    Gets an individual cafe
    */
    Route::get('/station/{id}',function (){
        return ['id'=> 1,'name'=>'name'];
    });

    /*
    |-------------------------------------------------------------------------------
    | Adds a New Cafe
    |-------------------------------------------------------------------------------
    | URL:            /api/v1/cafes
    | Controller:     API\CafesController@postNewCafe
    | Method:         POST
    | Description:    Adds a new cafe to the application
    */
    Route::post('/nets', function (){
        return [ 'id' => 'net one', 'name' => 'net name'];
    });
});