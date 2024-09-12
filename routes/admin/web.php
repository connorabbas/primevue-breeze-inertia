<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController as AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\NewPasswordController as AdminNewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController as AdminPasswordResetLinkController;

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::middleware(['guest', 'guest:admin'])->group(function () {
            Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])
                ->name('login');
            Route::post('login', [AdminAuthenticatedSessionController::class, 'store']);
            Route::get('forgot-password', [AdminPasswordResetLinkController::class, 'create'])
                ->name('password.request');
            Route::post('forgot-password', [AdminPasswordResetLinkController::class, 'store'])
                ->name('password.email');
            Route::get('reset-password/{token}', [AdminNewPasswordController::class, 'create'])
                ->name('password.reset');
            Route::post('reset-password', [AdminNewPasswordController::class, 'store'])
                ->name('password.store');
        });

        Route::middleware(['auth:admin'])->group(function () {
            Route::get('/dashboard', function () {
                return Inertia::render('Admin/Dashboard');
            })->name('dashboard');

            Route::post('logout', [AdminAuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
        });
    });
