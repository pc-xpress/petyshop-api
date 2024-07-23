<?php

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\Auth\LoginController;
use App\Http\Controllers\Api\v1\Auth\ProfileController;
use App\Http\Controllers\Api\v1\Auth\RegisterController;
use App\Http\Controllers\Api\v1\Auth\ResetPasswordController;
use App\Http\Controllers\Api\v1\Auth\UpdatePasswordController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers'], function () {
    # Auth
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/users', [RegisterController::class, 'store']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/password', [UpdatePasswordController::class, 'update']);
    Route::post('/reset-password', [ResetPasswordController::class, 'send']);
    Route::put('/reset-password', [ResetPasswordController::class, 'resetPassword']);
    # endAuth

    # Pets
    Route::middleware('auth:api')
        ->apiResource('/pets', PetController::class);
    #endPets

    # Posts
    Route::middleware('auth:api')
        ->get('/posts-public', [PostController::class, 'publicPosts']);

    Route::middleware('auth:api')
        ->as('pets')
        ->apiResource('pets/{pet:id}/posts', PostController::class);
    #endPosts
});
