<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'verified-admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Admin/Dashboard');
        })->name('dashboard');
    });

require __DIR__ . '/auth.php';
