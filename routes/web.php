<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SuppliesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'orders' => \App\Models\Order::latest()->get(),
        'supplies' => \App\Models\Supply::latest()->get(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/inventory', function () {
    return Inertia::render('Inventory');
})->middleware(['auth'])->name('inventory');

Route::get('/report', function () {
    return Inertia::render('Report');
})->middleware(['auth'])->name('report');

Route::get('/user', function () {
    return Inertia::render('User');
})->middleware(['auth'])->name('user');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    Route::get('/supplies', [SuppliesController::class, 'index'])->name('supplies.index');
    Route::get('/supplies/create', [SuppliesController::class, 'create'])->name('supplies.create');
    Route::post('/supplies', [SuppliesController::class, 'store'])->name('supplies.store');
    Route::get('/supplies/{supply}/edit', [SuppliesController::class, 'edit'])->name('supplies.edit');
    Route::put('/supplies/{supply}', [SuppliesController::class, 'update'])->name('supplies.update');
    Route::delete('/supplies/{supply}', [SuppliesController::class, 'destroy'])->name('supplies.destroy');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

require __DIR__ . '/auth.php';