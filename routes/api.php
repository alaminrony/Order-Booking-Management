<?php

use App\Http\Controllers\API\AddOBAController;
use App\Http\Controllers\API\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// If unauthenticated token redirect to this route
Route::get('un_authenticate', 'API\LoginController@un_authenticate')->name('un_authenticate');

// Login and signup
Route::post('login', 'API\LoginController@login')->name('loginAPI');
Route::post('register', 'API\LoginController@register')->name('registerAPI');


// All must authenticate Route will be here

Route::middleware('auth:api')->group(function () {
    Route::post('logout', 'API\LoginController@logout');
    Route::post('refresh', 'API\LoginController@refresh');
    Route::get('user', 'API\LoginController@me');

    Route::post('oba', [AddOBAController::class, 'store']);
    Route::get('oba/list', [AddOBAController::class, 'requestList']);
    Route::post('oba/reject', [AddOBAController::class, 'rejectOrder']);
    Route::post('oba/accept', [AddOBAController::class, 'acceptOrders']);
});
