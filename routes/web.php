<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ArController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;

// 1. Public Pages & Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// 2. Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. Shop & Product Catalog
Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');
Route::get('/shop/{slug}', [ProductController::class, 'show'])->name('shop.show');

// 4. AR Try-on Room
Route::get('/ar-tryon', [ArController::class, 'index'])->name('ar.tryon');

// 5. Authenticated Customer Routes
Route::middleware(['auth'])->group(function () {
    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{productId}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::post('/wishlist/move-to-cart/{id}', [WishlistController::class, 'moveToCart'])->name('wishlist.move-to-cart');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place-order');
    Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Dashboard & Profile
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/screenshot/delete', [DashboardController::class, 'deleteScreenshot'])->name('dashboard.delete-screenshot');

    // AR Save Screenshot
    Route::post('/ar-tryon/save-screenshot', [ArController::class, 'saveScreenshot'])->name('ar.save-screenshot');

    // Reviews CRUD
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{id}', [App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Newsletter Subscribe
Route::post('/newsletter/subscribe', [App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// AI Stylist Chatbot Message
Route::post('/ai-stylist/message', [App\Http\Controllers\AiStylistController::class, 'message'])->name('ai-stylist.message');

// 6. Admin Panel 
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Categories CRUD
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');

    // Products CRUD
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');

    // Orders Management
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::put('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.update-status');

    // Users Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

    // AR Assets Management
    Route::get('/ar-assets', [AdminController::class, 'arAssets'])->name('admin.ar-assets');
    Route::post('/ar-assets', [AdminController::class, 'uploadArAsset'])->name('admin.ar-assets.store');

    // Admin Reviews Moderation
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('admin.reviews');
    Route::delete('/reviews/{id}', [AdminController::class, 'deleteReview'])->name('admin.reviews.delete');

    // Admin Newsletter Subscribers
    Route::get('/subscribers', [AdminController::class, 'subscribers'])->name('admin.subscribers');
    Route::delete('/subscribers/{id}', [AdminController::class, 'deleteSubscriber'])->name('admin.subscribers.delete');
});
