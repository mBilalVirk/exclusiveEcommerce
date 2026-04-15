<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController; // Assuming you have this for 'show'
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

/*
|--------------------------------------------------------------------------
| User Routes (Must be Logged In)
|--------------------------------------------------------------------------
*/
Route::middleware([UserMiddleware::class])->group(function () {
    Route::get('/wishlist', function () { return view('user.wishlist.wishlist'); });
    
    Route::get('/checkout', function () { return view('user.checkout.checkout'); });
    Route::get('/account', function () { return view('user.account.account'); })->name('account');
    Route::post('/account', [AuthController::class, 'updateProfile'])->name('account.update');
    Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');


   
    
});
 Route::get('/products', [ProductController::class, 'index'])->name('products');
/*
|--------------------------------------------------------------------------
| Admin Routes (Role Restricted)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // You can easily add more admin routes here later
    // Route::get('/products', [AdminController::class, 'products'])->name('products');
});