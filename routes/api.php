<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\EmailChangeController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)->middleware(['signed'])->name('verification.verify');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::prefix('profile')->group(function () {
        Route::post('/update-name', [ProfileController::class, 'updateName']);
        Route::post('/update-username', [ProfileController::class, 'updateUsername']);
        Route::post('/update-password', [ProfileController::class, 'updatePassword']);
        Route::post('/disable', [ProfileController::class, 'disableAccount']);
        Route::delete('/delete', [ProfileController::class, 'deleteAccount']);
    });

    Route::prefix('email')->group(function () {
        Route::post('/send-verification', [VerifyEmailController::class, 'sendVerificationEmail']);
        Route::post('/request-change-code', [EmailChangeController::class, 'requestVerificationCode']);
        Route::post('/change', [EmailChangeController::class, 'updateEmail']);
    });
});
