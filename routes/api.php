<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\HospitalController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::middleware(['auth:api']) -> get('/current-user', [UserAuthController::class, 'getUser']);
    Route::middleware(['auth:api']) -> get('/logout', [UserAuthController::class, 'logout']);
});

Route::group(['prefix' => 'hospitals'], function () {
    Route::get('', [HospitalController::class, 'index']);
    Route::post('', [HospitalController::class, 'store']);
});

Route::group(['prefix' => 'bookings'], function() {
    Route::get('', [BookingsController::class, 'index']);
    Route::post('', [BookingsController::class, 'store']);
    Route::post('/update/{id}', [BookingsController::class, 'update']);
    Route::post('/delete/{id}', [BookingsController::class, 'destroy']);
});

// Route::apiResource('/hospitals', [HospitalController::class])->middleware('auth:api');
