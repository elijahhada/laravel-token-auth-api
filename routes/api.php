<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('me', [App\Http\Controllers\User\MeController::class, 'getMe']);

Route::group(['middleware' => ['auth:api']], function() {
    Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);
});

Route::group(['middleware' => ['guest:api']], function() {
    Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
    Route::post('verification/verify/{user}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('verification/resend', [App\Http\Controllers\Auth\VerificationController::class, 'resend']);
    Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset']);
});