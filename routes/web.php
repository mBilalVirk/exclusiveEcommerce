<?php

use App\Http\Controllers\AdminReviewsController;
use App\Http\Controllers\AdminAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerAdminController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\OrderAdminController;
use App\Http\Controllers\OrderCancellationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductAdminController;
use App\Http\Controllers\ProductController; // Assuming you have this for 'show'
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
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
Route::get('/cart', function () { return view('user.cart.cart'); })->name('cart');
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
    Route::post('/cart/add', [CartController::class, 'addToCart'])->middleware('throttle:30,1'); // 3 per minute;;
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart']);
    Route::get('/cartshow', [CartController::class, 'showCart'])->name('cart.show');
    Route::post('/cart/update/{id}', [CartController::class, 'updateQty']);


    // checkout routes
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show')->middleware('throttle:10,1'); // 10 per minute;
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('throttle:10,1'); // 10 per minute;
    Route::get('/order-confirmation/{orderId}', [CheckoutController::class, 'confirmation'])->name('order.confirmation');

    //Payment routes
    Route::get('/payment/{orderId}', [CheckoutController::class, 'paymentFailed'])->name('payment.failed');
    Route::post('/payment/process/{orderId}', [CheckoutController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/success/{orderId}', [CheckoutController::class, 'paymentSuccess'])->name('payment.success');

    // track my order
    Route::get('/track-order', [OrderController::class, 'trackOrder'])->name('track.order');
    //Search route
    Route::get('/live-search', [ProductController::class, 'liveSearch'])->name('liveSearch')->middleware('throttle:30,1');

    // Google Authentication Routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login') ->middleware('throttle:10,1');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('google.callback');


// Receipt Routes
 Route::get('/orders/{orderId}/receipt/download', [ReceiptController::class, 'downloadReceipt'])
        ->name('receipt.download');
    
    Route::get('/orders/{orderId}/receipt/view', [ReceiptController::class, 'viewReceipt'])
        ->name('receipt.view');

// Recommendation Route
Route::get('/recommendations/only-for-you', [RecommendationController::class, 'onlyForYou'])->name('recommendations.onlyForYou')->middleware('throttle:10,1');
//Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');
/*
|--------------------------------------------------------------------------
| Authentication Routes (Guest Only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('throttle:5,1'); // 5 attempts per minute;
    Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:5,1'); // 5 attempts per minute;

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('throttle:3,1'); // 3 per minute;
    Route::post('/register', [AuthController::class, 'register'])->name('register.post')->middleware('throttle:3,1'); // 3 per minute;
});

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| User Routes (Must be Logged In)
|--------------------------------------------------------------------------
*/
Route::middleware([UserMiddleware::class])->group(function () {
    // Checkout routes
    
    
    // Account routes
    Route::get('/account', function () { return view('user.account.account'); })->name('account');
    Route::post('/account', [AuthController::class, 'updateProfile'])->name('account.update')->middleware('throttle:3,1'); // 3 per minute;;
    Route::get('/account/orders', [OrderController::class, 'index'])->name('account.orders');
    // Order cancellation
    Route::get('/orders/{orderId}/cancel', [OrderCancellationController::class, 'showCancellation'])
        ->name('order.cancel.confirm');
    
    Route::post('/orders/{orderId}/cancel', [OrderCancellationController::class, 'cancelOrder'])
        ->name('order.cancel');

    // review
    Route::post('/reviews', [ReviewController::class, 'store'])
         ->name('reviews.store')
         ->middleware('throttle:5,1');
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
    Route::get('/admins', function () {
        return view('admin.admins');
    })->name('admins.view');

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

        // Admin Management
        Route::prefix('admins')->name('admins.')->group(function () {
            Route::get('/', [AdminAdminController::class, 'index'])->name('index');
            Route::post('/', [AdminAdminController::class, 'store'])->name('store');
            Route::get('/{id}', [AdminAdminController::class, 'show'])->name('show');
            Route::put('/{id}', [AdminAdminController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminAdminController::class, 'destroy'])->name('destroy');
        });
    });

    // Analytics
    Route::get('/analytics', [ProductAdminController::class, 'analytics'])->name('products.analytics');
});


// chatbot routes
Route::post('/chatbot', [ChatController::class, 'handle'])->name('chatbot.handle')->middleware('throttle:20,1'); // 20 per minute;



// Reports & Exports Routes
Route::middleware(['auth', 'admin'])->prefix('admin/reports')->name('reports.')->group(function () {
    // Dashboard
    Route::get('/', [ReportController::class, 'index'])->name('index');
    
    // Orders Export
    Route::get('/orders/csv', [ReportController::class, 'downloadOrdersCSV'])->name('export.orders.csv');
    Route::get('/orders/pdf', [ReportController::class, 'downloadOrdersPDF'])->name('export.orders.pdf');
    
    // Customers Export
    Route::get('/customers/csv', [ReportController::class, 'downloadCustomersCSV'])->name('export.customers.csv');
    Route::get('/customers/pdf', [ReportController::class, 'downloadCustomersPDF'])->name('export.customers.pdf');
    
    // Products Export
    Route::get('/products/csv', [ReportController::class, 'downloadProductsCSV'])->name('export.products.csv');
    
    // Revenue Report
    Route::get('/revenue', [ReportController::class, 'downloadRevenueReport'])->name('export.revenue');
    
    // Tax Report
    Route::get('/tax', [ReportController::class, 'downloadTaxReport'])->name('export.tax');
});




Route::get('/products/{product}/reviews', [ReviewController::class, 'show'])
     ->name('products.reviews');


// === ADMIN REVIEWS ROUTES ===
Route::prefix('admin/reviews')->group(function () {
    Route::get('/', [AdminReviewsController::class, 'index'])->name('admin.reviews.index');
    Route::get('/{review}', [AdminReviewsController::class, 'show'])->name('admin.reviews.show');
    Route::post('/{review}/approve', [AdminReviewsController::class, 'approve'])->name('admin.reviews.approve');
    Route::post('/{review}/unapprove', [AdminReviewsController::class, 'unapprove'])->name('admin.reviews.unapprove');
    Route::delete('/{review}', [AdminReviewsController::class, 'destroy'])->name('admin.reviews.destroy');

    // Bulk Actions
    Route::post('/bulk-approve', [AdminReviewsController::class, 'bulkApprove'])->name('bulk.approve');
    Route::post('/bulk-delete', [AdminReviewsController::class, 'bulkDelete'])->name('bulk.delete');
});