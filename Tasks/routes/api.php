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
    Route::put('/user', [\App\Http\Controllers\UserController::class, 'update']);
    Route::resource('/project', \App\Http\Controllers\ProjectController::class);
    Route::resource('/category', \App\Http\Controllers\CategoryController::class);
    Route::prefix('task')->controller(\App\Http\Controllers\TaskController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{task}', 'show');
        Route::post('/', 'store');
        Route::put('/{task}', 'update');
        Route::put('/status/{task}', 'status');
        Route::put('/progress/{task}', 'progress');
        Route::delete('/{task}', 'destroy');
    });
    Route::prefix('users')->controller(\App\Http\Controllers\UserInProjectController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/invitations', 'invitations');
        Route::post('/', 'store');
        Route::put('/role', 'update');
        Route::put('/accept', 'accept');
        Route::delete('/', 'destroy');
    });
    Route::post('/performer', [\App\Http\Controllers\PerformerController::class, 'store']);
    Route::delete('/performer', [\App\Http\Controllers\PerformerController::class, 'destroy']);
});

