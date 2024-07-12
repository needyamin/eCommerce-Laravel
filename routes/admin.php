<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Product\ProductController;


# Prefix all routes with 'Admin prefix for Dashboard'
// Route::prefix('admin')->group(function () {
// Routes links
// });


Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');
#Route::resource('product', ProductController::class);

// Products Resource route will be admin.dashboard.index
Route::resource('product', ProductController::class)->names([
        'index' => 'dashboard.index',
        'create' => 'dashboard.create',
        'store' => 'dashboard.store',
        'show' => 'dashboard.show',
        'edit' => 'dashboard.edit',
        'update' => 'dashboard.update',
        'destroy' => 'dashboard.destroy',
    ]);


