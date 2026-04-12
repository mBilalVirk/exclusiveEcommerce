<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('user.home');
});

Route::get('/login', function () {
    return view('user.login');
});
Route::get('/register', function () {
    return view('user.register');
});

Route::get('/wishlist', function () {
    return view('user.wishlist.wishlist');
});
Route::get('/cart', function () {
    return view('user.cart.cart');
});
Route::get('/checkout', function () {
    return view('user.checkout.checkout');
});
Route::get('/account', function () {
    return view('user.account.account');
});
Route::get('/about', function () {
    return view('user.about.about');
});
Route::get('/contact', function () {
    return view('user.contact.contact');
});

Route::get('/test-404', function () {
    abort(404);
});
Route::get('/show', function () {
    return view('user.product-details.product-details');
});

Route::get('/admin', function () {
    return view('admin.dashboard');
});


