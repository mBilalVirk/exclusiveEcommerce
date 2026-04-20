<?php

use App\Http\Controllers\CustomerAdminController;
use App\Http\Controllers\OrderAdminController;
use App\Http\Controllers\ProductAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController; // Assuming you have this for 'show'
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () { return view('user.home'); })->name('home');
Route::get('/about', function () { return view('user.about.about'); });
Route::get('/contact', function () { return view('user.contact.contact'); });
Route::get('/show', function () { return view('user.product-details.product-details'); });
Route::get('/test-404', function () { abort(404); });
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/flashsales', [ProductController::class, 'flashsales'])->name('flashsales');
Route::get('/products/bestselling', [ProductController::class, 'bestSelling'])->name('bestselling');
Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
Route::get('/cart', function () { return view('user.cart.cart'); });
Route::get('/show/{id}', function ($id) {
    return view('user.product-details.product-details', compact('id'));
});
Route::get('/products/{id}', [ProductController::class, 'show'])->name('productDetails');//single item details
// wishlist toggle
Route::get('/wishlist', function () { return view('user.wishlist.wishlist'); });
Route::post('/wishlist/toggle/{id}', [WishlistController::class, 'toggle']);
   Route::get('/wishlist/count', [WishlistController::class,'wishListCount']);
    Route::get('/wishlists', [WishlistController::class, 'showWishList'])->name('show.WishList');
// add to cart 
Route::get('/cart/count', [CartController::class,'cartCount']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart']);
    Route::get('/cartshow', [CartController::class, 'showCart'])->name('cart.show');
    Route::post('/cart/update/{id}', [CartController::class, 'updateQty']);
/*
|--------------------------------------------------------------------------
| Authentication Routes (Guest Only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| User Routes (Must be Logged In)
|--------------------------------------------------------------------------
*/
Route::middleware([UserMiddleware::class])->group(function () {
    
    
    Route::get('/checkout', function () { return view('user.checkout.checkout'); });
    Route::get('/account', function () { return view('user.account.account'); })->name('account');
    Route::post('/account', [AuthController::class, 'updateProfile'])->name('account.update');
    


   
    
});
//  Route::get('/products', [ProductController::class, 'index'])->name('products');
/*
|--------------------------------------------------------------------------
| Admin Routes (Role Restricted)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Admin Views
    Route::get('/products', function () {
        return view('admin.products');
    })->name('products.view');
    Route::get('/orders', function () {
        return view('admin.orders');
    })->name('orders.view');
    Route::get('/customers', function () {
        return view('admin.customers');
    })->name('customers.view');

    // API Routes for Admin Panel
    Route::prefix('api')->name('api.')->group(function () {
        // Products Management
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [ProductAdminController::class, 'index'])->name('index');
            Route::post('/', [ProductAdminController::class, 'store'])->name('store');
            Route::get('/{id}', [ProductAdminController::class, 'show'])->name('show');
            Route::put('/{id}', [ProductAdminController::class, 'update'])->name('update');
            Route::delete('/{id}', [ProductAdminController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-update-stock', [ProductAdminController::class, 'bulkUpdateStock'])->name('bulk-update-stock');
        });

        // Orders Management
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderAdminController::class, 'index'])->name('index');
            Route::post('/', [OrderAdminController::class, 'store'])->name('store');
            Route::get('/{id}', [OrderAdminController::class, 'show'])->name('show');
            Route::put('/{id}/status', [OrderAdminController::class, 'updateStatus'])->name('update-status');
            Route::delete('/{id}', [OrderAdminController::class, 'destroy'])->name('destroy');
            Route::get('/statistics', [OrderAdminController::class, 'statistics'])->name('statistics');
        });

        // Customer Management (CRM)
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/', [CustomerAdminController::class, 'index'])->name('index');
            Route::get('/{id}', [CustomerAdminController::class, 'show'])->name('show');
            Route::put('/{id}', [CustomerAdminController::class, 'update'])->name('update');
            Route::get('/{id}/lifetime-value', [CustomerAdminController::class, 'lifetimeValue'])->name('lifetime-value');
            Route::get('/segmentation', [CustomerAdminController::class, 'segmentation'])->name('segmentation');
            Route::post('/{id}/send-message', [CustomerAdminController::class, 'sendMessage'])->name('send-message');
        });
    });
});