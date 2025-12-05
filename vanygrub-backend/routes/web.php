<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Test route for Vercel
Route::get('/test', function () {
    return response()->json([
        'message' => 'VANYGRUB Laravel is working on Vercel!',
        'timestamp' => now(),
        'env' => app()->environment()
    ]);
});

// Frontend Routes

Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/vny', [FrontendController::class, 'vnyStore'])->name('vny.store');
Route::get('/gallery', [FrontendController::class, 'gallery'])->name('gallery');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/transactions', [FrontendController::class, 'transactions'])->name('transactions');

// Product Routes
Route::get('/vny/product', [FrontendController::class, 'productList'])->name('vny.product');
Route::get('/vny/products', [FrontendController::class, 'productList'])->name('vny.products'); // Alias for consistency
Route::get('/vny/product/{id}', [FrontendController::class, 'productDetail'])->name('product.detail');
Route::get('/category/{slug}', [FrontendController::class, 'categoryProducts'])->name('category.products');
Route::get('/search', [FrontendController::class, 'search'])->name('search');

// Brand About Routes
Route::get('/vny/about', [FrontendController::class, 'vnyAbout'])->name('vny.about');
Route::get('/vanysongket/about', [FrontendController::class, 'vanysongketAbout'])->name('vanysongket.about');
Route::get('/vanyvilla/about', [FrontendController::class, 'vanyvillaAbout'])->name('vanyvilla.about');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('about-settings', App\Http\Controllers\Admin\AboutSettingController::class);
});

// Cart Route
Route::get('/vny/cart', [FrontendController::class, 'cart'])->name('vny.cart');

// Tailwind Test Route
Route::get('/tailwind-test', function () {
    return view('pages.tailwind-test');
})->name('tailwind.test');

// Firebase Test Route
Route::get('/firebase-test', function () {
    return view('pages.firebase-test');
})->name('firebase.test');

// SEO Routes
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [App\Http\Controllers\SitemapController::class, 'robots'])->name('robots');
Route::get('/sitemap', function () {
    return view('pages.sitemap');
})->name('sitemap.html');

// Checkout Routes
Route::get('/checkout/{code?}', [FrontendController::class, 'checkout'])->name('checkout');

// Review Routes
Route::get('/customerreview', [FrontendController::class, 'customerReview'])->name('customer.review');
Route::get('/customerreview/all', [FrontendController::class, 'allCustomerReviews'])->name('customer.review.all');

// ubah dikit
// Order invoice route
Route::get('/orders/{order}/invoice', [\App\Http\Controllers\OrderController::class, 'invoice'])
    ->name('orders.invoice');

// Customer Review routes
Route::get('/review/{token}', [App\Http\Controllers\ReviewController::class, 'show'])->name('review.form');
Route::post('/review/{token}/store', [App\Http\Controllers\ReviewController::class, 'store'])->name('review.store');

// QR Batch Routes
Route::get('/qr/batch/paper-selection', [App\Http\Controllers\QrBatchController::class, 'selectPaper'])->name('qr.batch.paper-selection');
Route::get('/qr/batch/preview', [App\Http\Controllers\QrBatchController::class, 'preview'])->name('qr.batch.preview');

// Debug session untuk troubleshooting (hapus di production)
Route::get('/debug/session', function () {
    return response()->json([
        'session_id' => session()->getId(),
        'session_name' => session()->getName(),
        'session_started' => session()->isStarted(),
        'csrf_token' => csrf_token(),
        'app_env' => config('app.env'),
        'app_url' => config('app.url'),
        'session_config' => [
            'driver' => config('session.driver'),
            'lifetime' => config('session.lifetime'),
            'domain' => config('session.domain'),
            'secure' => config('session.secure'),
            'http_only' => config('session.http_only'),
            'same_site' => config('session.same_site'),
            'cookie' => config('session.cookie'),
        ],
        'request_info' => [
            'url' => request()->url(),
            'is_secure' => request()->isSecure(),
            'scheme' => request()->getScheme(),
            'host' => request()->getHost(),
        ]
    ]);
});
