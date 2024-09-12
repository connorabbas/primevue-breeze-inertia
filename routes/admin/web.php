<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\NewPasswordController as AdminNewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController as AdminPasswordResetLinkController;

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Authentication
        Route::controller(AuthenticatedSessionController::class)->group(function () {
            Route::get('/login', 'create')
                ->middleware(['guest', 'guest:admin'])
                ->name('login');
            Route::post('/login', 'store');
            Route::post('/logout', 'destroy')
                ->middleware(['auth:admin'])
                ->name('logout');
        });
        Route::get('forgot-password', [AdminPasswordResetLinkController::class, 'create'])
            ->name('password.request');
        Route::post('forgot-password', [AdminPasswordResetLinkController::class, 'store'])
            ->name('password.email');
        Route::get('reset-password/{token}', [AdminNewPasswordController::class, 'create'])
            ->name('password.reset');
        Route::post('reset-password', [AdminNewPasswordController::class, 'store'])
            ->name('password.store');

        // Authorized Routes
        Route::middleware(['auth:admin'])->group(function () {
            Route::get('/dashboard', function () {
                return Inertia::render('Admin/Dashboard');
            })->name('dashboard');
        });
    });
