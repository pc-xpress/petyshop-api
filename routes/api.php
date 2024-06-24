<?php

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\Auth\LoginController;
use App\Http\Controllers\Api\v1\Auth\ProfileController;
use App\Http\Controllers\Api\v1\Auth\RegisterController;
use App\Http\Controllers\Api\v1\Auth\UpdatePasswordController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');Create route to reset password method


Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers'], function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/users', [RegisterController::class, 'store']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/password', [UpdatePasswordController::class, 'update']);
    Route::put('/reset-password', [ResetPasswordController::class, 'update']);
});
