<?php

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

Route::controller(\App\Http\Controllers\UserController::class)->group(function() {
    Route::post('register', 'register');
});

Route::prefix('auth')->middleware('api')->controller(\App\Http\Controllers\AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::get('user', 'user');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::resource('/project', \App\Http\Controllers\ProjectController::class);
    Route::resource('/category', \App\Http\Controllers\CategoryController::class);
    Route::resource('/task', \App\Http\Controllers\TaskController::class);
});

