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

Route::group(['prefix' => 'v1'], function()  //,'middleware' => 'auth:api'
{
    Route::post('/getAuthUser',
        [
            'as'=>'v1.getAuthUser',
            'uses'=>'API\AlertSystemController@getAuthUser'
        ]);

    Route::post('/stations', [ 'as'=>'v1.stations','uses'=>'API\AlertSystemController@getStations']);

    Route::post('/station', [ 'as'=>'v1.station','uses'=>'API\AlertSystemController@getStation']);

    Route::get('/nets', [ 'as'=>'v1.nets','uses'=>'API\AlertSystemController@getNets']);

    Route::post('/alerts', [ 'as'=>'v1.alerts','uses'=>'API\AlertSystemController@getAlerts']);

    Route::get('/typeStation', [ 'as'=>'v1.typeStation','uses'=>'API\AlertSystemController@getTypeStation']);

    Route::post('/consultAlert', [ 'as'=>'v1.consultAlert','uses'=>'API\AlertSystemController@consultAlert']);

});


Route::group(['prefix' => 'v2'], function()  //,'middleware' => 'auth:api'
{

    # New Version
    Route::post('/landslideInformation', [ 'as'=>'v2.landslideInformation','uses'=>'API\AccessAlertSystemController@landslideInformation']);
    Route::post('/floodInformation', [ 'as'=>'v2.floodInformation','uses'=>'API\AccessAlertSystemController@floodInformation']);
    Route::post('/userInformation', [ 'as'=>'v2.userInformation','uses'=>'API\AccessAlertSystemController@userInformation']);

    # Old Version
/*
    Route::post('/getStationsAlertLandslide', [ 'as'=>'v2.getStationsAlertLandslide','uses'=>'API\AccessAlertSystemController@getStationsAlertLandslide']);
    Route::post('/getStationsAlertFlood', [ 'as'=>'v2.getStationsAlertFlood','uses'=>'API\AccessAlertSystemController@getStationsAlertFlood']);

    Route::post('/getTrackingLandslide', [ 'as'=>'v2.getTrackingLandslide','uses'=>'API\AccessAlertSystemController@getTrackingLandslide']);
    Route::post('/getTrackingFlood', [ 'as'=>'v2.getTrackingFlood','uses'=>'API\AccessAlertSystemController@getTrackingFlood']);

    Route::post('/getAuthUser', [ 'as'=>'v2.getAuthUser','uses'=>'API\AccessAlertSystemController@getAuthUser']);

    Route::post('/getRoleAuthUser', [ 'as'=>'v2.getRole','uses'=>'API\AccessAlertSystemController@getRoleAuthUser']);

    Route::post('/getPermissions', [ 'as'=>'v2.getPermissions','uses'=>'API\AccessAlertSystemController@getPermissions']);

    Route::post('/getUserPermissions', [ 'as'=>'v2.getUserPermissions','uses'=>'API\AccessAlertSystemController@getUserPermissions']);

    Route::post('/stations', [ 'as'=>'v2.stations','uses'=>'API\AccessAlertSystemController@getStations']);

    Route::post('/station', [ 'as'=>'v2.station','uses'=>'API\AccessAlertSystemController@getStation']);

    Route::get('/nets', [ 'as'=>'v2.nets','uses'=>'API\AccessAlertSystemController@getNets']);

    Route::post('/alerts', [ 'as'=>'v2.alerts','uses'=>'API\AccessAlertSystemController@getAlerts']);

    Route::get('/typeStation', [ 'as'=>'v2.typeStation','uses'=>'API\AccessAlertSystemController@getTypeStation']);

    Route::post('/consultAlert', [ 'as'=>'v2.consultAlert','uses'=>'API\AccessAlertSystemController@consultAlert']);
*/
});