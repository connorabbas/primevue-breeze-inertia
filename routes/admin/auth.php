<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::middleware(['guest', 'guest:admin'])->group(function () {
            Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');
            Route::post('login', [AuthenticatedSessionController::class, 'store']);
            Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');
            Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');
            Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');
            Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
        });

        Route::middleware(['auth:admin'])->group(function () {
            Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');
            Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');
            Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');
            Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
        });
    });
