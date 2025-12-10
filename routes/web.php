<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{id}', [ProductController::class, 'byCategory'])->name('products.category');
Route::get('/brand/{id}', [ProductController::class, 'byBrand'])->name('products.brand');

// Customer Authentication
Route::middleware('guest:customer')->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.submit');
});

Route::middleware('auth:customer')->group(function () {
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [CustomerAuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [CustomerAuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [CustomerAuthController::class, 'updatePassword'])->name('profile.password');
    
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
    
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Authentication
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });
    
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        
        // Products
        Route::resource('products', AdminProductController::class)->except(['destroy']);
        Route::get('/products/{id}/delete', [AdminProductController::class, 'destroy'])->name('products.destroy');
        Route::get('/subcategories/{categoryId}', [AdminProductController::class, 'getSubcategories'])->name('subcategories');
        Route::get('/products/{id}/images', [AdminProductController::class, 'manageImages'])->name('products.images');
        Route::post('/products/variants/{variantId}/images', [AdminProductController::class, 'uploadImages'])->name('products.images.upload');
        Route::get('/products/images/{imageId}/delete', [AdminProductController::class, 'deleteImage'])->name('products.images.delete');
        
        // Categories
        Route::resource('categories', CategoryController::class)->except(['destroy']);
        Route::get('/categories/{id}/delete', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::post('/categories/{id}/subcategories', [CategoryController::class, 'storeSubcategory'])->name('subcategories.store');
        Route::get('/subcategories/{id}/delete', [CategoryController::class, 'destroySubcategory'])->name('subcategories.destroy');
        
        // Brands
        Route::resource('brands', BrandController::class)->except(['destroy']);
        Route::get('/brands/{id}/delete', [BrandController::class, 'destroy'])->name('brands.destroy');
        
        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
        Route::put('/orders/{id}/payment', [AdminOrderController::class, 'updatePayment'])->name('orders.payment');
        
        // Customers
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    });
});
