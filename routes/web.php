<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/product/{slug}', [\App\Http\Controllers\PageController::class, 'product'])->name('product.show');
// Cart Routes
Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

// Checkout Routes
Route::post('/checkout/apply-coupon', [\App\Http\Controllers\CheckoutController::class, 'applyCoupon'])->name('checkout.apply-coupon');
Route::post('/checkout/remove-coupon', [\App\Http\Controllers\CheckoutController::class, 'removeCoupon'])->name('checkout.remove-coupon');
Route::post('/checkout/process', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{order}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');

Route::middleware(['auth'])->group(function () {
    Route::post('/wishlist/toggle', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/customer/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('customer.wishlist');
});

Route::get('/category/{cat_id?}', [PageController::class, 'category'])->name('category');
Route::get('/brand/{brand_id?}', [PageController::class, 'brand'])->name('brand');
Route::post('/subscribe-coupon', [\App\Http\Controllers\CouponController::class, 'subscribe'])->name('coupon.subscribe');
Route::get('/cart', [PageController::class, 'cart'])->name('cart');
Route::get('/track-order', [PageController::class, 'trackOrder'])->name('track.order');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [PageController::class, 'checkout'])->name('checkout');

    // Customer Portal Routes
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\CustomerController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [\App\Http\Controllers\CustomerController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [\App\Http\Controllers\CustomerController::class, 'showOrder'])->name('orders.show');
        Route::post('/orders/{order}/refund', [\App\Http\Controllers\CustomerController::class, 'requestRefund'])->name('orders.refund');
    });

    // Protected Admin Routes
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
        Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class)->except(['show']);
        Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class)->except(['show']);
        Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class)->except(['show']);
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->except(['show']);
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show']);
        Route::put('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status.update');
        Route::get('orders/{order}/print', [\App\Http\Controllers\Admin\OrderController::class, 'print'])->name('orders.print');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/address', [ProfileController::class, 'address'])->name('profile.address');
    Route::patch('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.address.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
