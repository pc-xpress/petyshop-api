<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Auth\AuthController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    //Route::post('/login', LoginController::class, 'login');
    // Route::apiResource('customers', CustomerController::class);
    // Route::apiResource('pets', PetController::class);
    // Route::post('pets/bulk', ['uses' => 'PetController@bulkStore']);
});
