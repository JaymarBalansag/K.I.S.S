<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [App\Http\Controllers\Api\AuthenticationController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(App\Http\Controllers\Api\AuthenticationController::class)->group(function () {
        Route::post('/add-user', 'AddUser');
        Route::post('/logout', 'logout');
    });

    Route::middleware("is_admin")->group(function () {
        Route::apiResource('Staffs', \App\Http\Controllers\UserController::class);
    });


});

