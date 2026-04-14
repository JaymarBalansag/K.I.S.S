<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SmsRequestController;
use App\Http\Controllers\Api\MarriageApplicationController;
use App\Http\Controllers\Api\MarriageLicenseApplicationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/ping', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'pong',
        'timestamp' => now()->toIso8601String(),
    ], 200);
});

Route::get('/download', [App\Http\Controllers\PdfController::class, 'generateLicense']);
Route::post('/login', [App\Http\Controllers\Api\AuthenticationController::class, 'login']);
Route::post('/sms/login', [App\Http\Controllers\Api\AuthenticationController::class, 'smsLogin']);

Route::controller(MarriageApplicationController::class)->group(function () {
    Route::post('/submit/marriage-license-application', 'store');
});
Route::get('/Appointments/availability', [App\Http\Controllers\AppointmentController::class, 'availability']);
Route::apiResource('Appointments', App\Http\Controllers\AppointmentController::class)->only(['store']);
Route::get('/applications/print/{id}/{control_number}', [MarriageApplicationController::class, 'printApplication']);
Route::get('/pdf/8x13-preview', [MarriageApplicationController::class, 'preview8x13'])->name('pdf.8x13.preview');
Route::get('/pdf/8x13-preview-pdf', [MarriageApplicationController::class, 'preview8x13Pdf'])->name('pdf.8x13.preview.pdf');
Route::middleware('sms_key')->group(function () {
    Route::get('/sms-requests/pending', [SmsRequestController::class, 'getPending']);
    Route::put('/sms-requests/{id}/ack', [SmsRequestController::class, 'acknowledge']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(App\Http\Controllers\Api\AuthenticationController::class)->group(function () {
        Route::post('/add-user', 'AddUser');
        Route::post('/logout', 'logout');
        Route::post('/sms/logout', 'logout');
    });

    Route::middleware('is_admin')->group(function () {
        Route::apiResource('Staffs', App\Http\Controllers\UserController::class);
        Route::apiResource('sms-requests', SmsRequestController::class);

        Route::delete('/applications/{id}', [MarriageApplicationController::class, 'destroy']);
        Route::get('/applications/trash', [MarriageApplicationController::class, 'trash']);
        Route::patch('/applications/{id}/restore', [MarriageApplicationController::class, 'restore']);
        Route::patch('/applications/{id}', [MarriageApplicationController::class, 'adminUpdate']);
        Route::delete('/applications/{id}/force', [MarriageApplicationController::class, 'forceDestroy']);

        Route::get('/Appointments/trash', [App\Http\Controllers\AppointmentController::class, 'trash']);
        Route::patch('/Appointments/{id}/restore', [App\Http\Controllers\AppointmentController::class, 'restore']);
        Route::delete('/Appointments/{id}/force', [App\Http\Controllers\AppointmentController::class, 'forceDestroy']);
    });

    Route::middleware('is_staff')->group(function () {
        Route::apiResource('Appointments', App\Http\Controllers\AppointmentController::class)->except(['store']);
        Route::controller(MarriageApplicationController::class)->group(function () {
            Route::get('/applications', 'getApplications');
            Route::get('/view/applicants/{application_id}/{control_number}', 'viewApplication');
            Route::get('/applications/{status}/{order}', 'getApplicationByStatus');
            Route::post('/applications/{action}', 'ApplicationAction');
        });
    });
});
