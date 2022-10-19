<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
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

Route::group(['prefix' => 'auth'], function(){
    Route::post("login", [AuthController::class, 'login']);
    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });
    Route::group(['middleware' => 'jwt.refresh'], function () {
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
});


Route::group([
    'prefix' => 'users',
    'middleware' => 'jwt.auth',
], function(){
    Route::controller(UserController::class)->group(function(){
       Route::get('', 'index');
       Route::get('/{id}', 'view');
       Route::post('', 'store');
       Route::post('/{id}', 'update');
       Route::delete('/{id}', 'destroy');
       Route::post('/{id}/restore-user', 'restore');
    });
});


Route::group([
    'prefix' => 'news',
    'middleware' => 'jwt.auth',
], function(){
    Route::controller(NewsController::class)->group(function(){
        Route::get('', 'index');
        Route::get('/{id}', 'view');
        Route::post('', 'store');
        Route::post('/{id}', 'update');
        Route::post('/{id}/change-visibility', 'changeVisibility');
        Route::post('/{id}/change-position', 'changePosition');
        Route::delete('/{id}', 'destroy');
        Route::post('/{id}/restore-news', 'restore');
    });
});