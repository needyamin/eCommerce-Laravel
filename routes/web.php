<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Product\ProductController;

#export NODE_OPTIONS=--max-old-space-size=8192 
##### php artisan serve --host 192.168.68.196 --port 8000
#### php artisan migrate:refresh --path=/database/migrations/2024_03_16_102807_create_leave_application.php

// NPM //
## npm install vuex --save
## npm install jquery bootstrap
##

Auth::routes();

// eCommerce Homepage
Route::get('/', function () { return view('webpages.users.home');});
// eCommerce Shopping Cart Page
Route::get('/cart', function () { return view('webpages.users.checkout');});

# Prefix all routes with 'Admin prefix for Dashboard'
Route::prefix('admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    #Route::resource('product', ProductController::class);
    // Products Resource
    Route::resource('product', ProductController::class)->names([
        'index' => 'admin.dashboard.index',
        'create' => 'admin.dashboard.create',
        'store' => 'admin.dashboard.store',
        'show' => 'admin.dashboard.show',
        'edit' => 'admin.dashboard.edit',
        'update' => 'admin.dashboard.update',
        'destroy' => 'admin.dashboard.destroy',
    ]);




});



Route::group(['middleware' => ['auth', 'role:admin']], function () {
    // Route::get('/admin', function () {return redirect('/login');});
});

Route::group(['middleware' => ['auth', 'checkPermission:create']], function () {
    // Route::get('/admin', function () {return redirect('/login');});
});


