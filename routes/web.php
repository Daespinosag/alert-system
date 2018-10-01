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

Route::get('/', function () { return view('test'); })->middleware('auth');

Route::group(['prefix' => 'test','name' => 'test'], function()
{
    Route::get('index', [ 'as'=>'test.index','uses'=>'AlertSystem\testController@index']);
    //Route::get('index', [ 'as'=>'test.index','uses'=>'API\AlertSystemController@getStations']);
});

Auth::routes();

Route::get('/register/verify/{code}', 'Auth\GuestController@verify');
Route::get('/reConfirmation/index', [ 'as'=>'reConfirmation.index','uses'=> 'Auth\GuestController@reConfirmationIndex']);
Route::post('/reConfirmation/sedEmail', [ 'as'=>'reConfirmation.sedEmail','uses'=> 'Auth\GuestController@reConfirmationSendEmail']);

Route::get('/logout', function (){
    Auth::logout();
    return redirect('/login');
});