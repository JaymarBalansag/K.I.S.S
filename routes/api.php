<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MarriageApplicationController;
use App\Http\Controllers\Api\MarriageLicenseApplicationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [App\Http\Controllers\Api\AuthenticationController::class, 'login']);

Route::controller(MarriageApplicationController::class)->group(function () {
    Route::post("/submit/marriage-license-application", "store");
});

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(App\Http\Controllers\Api\AuthenticationController::class)->group(function () {
        Route::post('/add-user', 'AddUser');
        Route::post('/logout', 'logout');
    });

    Route::middleware("is_admin")->group(function () {
        Route::apiResource('Staffs', \App\Http\Controllers\UserController::class);
    });

    Route::middleware("is_staff")->group(function () {
        Route::controller(MarriageApplicationController::class)->group(function() {
            Route::get("/applications", "getApplications");
            Route::get("/view/applicants/{application_id}/{control_number}", "viewApplication");
        });
    });


});

