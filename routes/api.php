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

Route::group([ 'prefix' => 'auth'], function (){
    Route::group(['middleware' => []], function () {
        Route::post('login', 'API\AuthController@login');
        Route::post('signup', 'API\AuthController@signup');
    });
    Route::group(['middleware' => ['auth:api']], function() {
        Route::get('logout', 'API\AuthController@logout');
        Route::get('getuser', 'API\AuthController@getUser');
    });
});

Route::group(['prefix' => 'hospitals'], function() {
    Route::get('', 'API\HospitalController@index');
    Route::post('', 'API\HospitalController@store');
});

Route::group(['prefix' => 'bookings'], function() {
    Route::get('', 'API\BookingController@index');
    Route::post('', 'API\BookingController@store');
});
