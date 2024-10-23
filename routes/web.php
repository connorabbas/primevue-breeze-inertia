<?php

use App\Http\Controllers\PurchaseOrderController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'isAdmin' => Auth::guard('admin')->check(),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

Route::prefix('purchase-orders')->name('purchase-orders.')->group(function () {
    Route::get('/', [PurchaseOrderController::class, 'index'])->name('index');
    Route::get('/create', [PurchaseOrderController::class, 'create'])->name('create');
    Route::post('/', [PurchaseOrderController::class, 'store'])->name('store');
    Route::post('/draft', [PurchaseOrderController::class, 'draft'])->name('draft');
    Route::get('/{id}', [PurchaseOrderController::class, 'show'])->name('show');
});

require __DIR__ . '/user/web.php';
require __DIR__ . '/admin/web.php';
