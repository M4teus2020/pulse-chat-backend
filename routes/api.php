<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)->middleware(['signed'])->name('verification.verify');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::prefix('profile')->group(function () {
        Route::post('/update-name', [ProfileController::class, 'updateName']);
        Route::post('/update-username', [ProfileController::class, 'updateUsername']);
        Route::post('/update-password', [ProfileController::class, 'updatePassword']);
        Route::post('/disable', [ProfileController::class, 'disableAccount']);
        Route::delete('/delete', [ProfileController::class, 'deleteAccount']);
    });

    Route::post('/email/send-verification', [VerifyEmailController::class, 'sendVerificationEmail'])->middleware(['auth:sanctum']);
});
