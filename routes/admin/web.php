<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;

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

        // Authorized
        Route::middleware(['auth:admin'])->group(function () {
            Route::get('/dashboard', function () {
                return Inertia::render('Admin/Dashboard');
            })->name('dashboard');
        });
    });
