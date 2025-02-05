<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/update-password', [AuthController::class, 'updatePassword']);
    Route::post('/update-username', [AuthController::class, 'updateUsername']);
    Route::post('/disable-account', [AuthController::class, 'disableAccount']);
    Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);
});
