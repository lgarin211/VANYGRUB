<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DataController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\HeroSectionController;
use App\Http\Controllers\Api\ProductGridController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\SessionDebugController;
use App\Http\Controllers\Api\HomepageController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\CustomerReviewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// VANY GROUB API Routes with prefix /vny/
Route::prefix('vny')->group(function () {
    // Data endpoints for Next.js constants
    Route::get('data', [DataController::class, 'getVnyData']);
    Route::get('home-data', [DataController::class, 'getHomeData']);

    // Homepage constants API
    Route::get('homepage/constants', [HomepageController::class, 'getConstants']);
    Route::get('homepage/gallery/{id}', [HomepageController::class, 'getGalleryItem']);
    Route::get('homepage/category/{category}', [HomepageController::class, 'getGalleryByCategory']);
    Route::get('homepage/site-config', [DataController::class, 'getVnySiteConfig']);

    // Categories API
    Route::apiResource('categories', CategoryController::class);

    // Products API
    Route::apiResource('products', ProductController::class);
    Route::get('products/slug/{slug}', [ProductController::class, 'getBySlug']);
    Route::get('featured-products', [ProductController::class, 'getFeatured']);

    // Cart API
    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart', [CartController::class, 'store']);
    Route::put('cart/{id}', [CartController::class, 'update']);
    Route::delete('cart/{id}', [CartController::class, 'destroy']);
    Route::match(['POST', 'DELETE'], 'cart/clear', [CartController::class, 'clear']);

    // Orders API
    Route::apiResource('orders', OrderController::class);
    Route::get('orders/code/{orderCode}', [OrderController::class, 'getByOrderCode']);
    Route::get('orders/stats/summary', [OrderController::class, 'getStats']);
    Route::get('orders/{id}/review-qr', [OrderController::class, 'getReviewQR']);

    // Hero Sections API
    Route::apiResource('hero-sections', HeroSectionController::class);

    // Product Grid API
    Route::apiResource('product-grids', ProductGridController::class);
    Route::get('product-grid-data', [ProductGridController::class, 'getGridData']);

    // Media/Gallery Upload API
    Route::prefix('media')->group(function () {
        Route::post('upload', [MediaController::class, 'uploadSingle']);
        Route::post('upload-multiple', [MediaController::class, 'uploadMultiple']);
        Route::delete('delete', [MediaController::class, 'deleteMedia']);
        Route::get('list', [MediaController::class, 'getMedia']);
        Route::get('debug/{id}', [MediaController::class, 'debugFileInfo']);
    });

    // Customer Reviews API
    Route::prefix('reviews')->group(function () {
        Route::get('approved', [CustomerReviewController::class, 'getApprovedReviews']);
        Route::get('featured', [CustomerReviewController::class, 'getFeaturedReviews']);
        Route::get('{token}', [CustomerReviewController::class, 'showReviewForm']);
        Route::post('{token}/submit', [CustomerReviewController::class, 'submitReview']);
        Route::post('batch-qr', [CustomerReviewController::class, 'batchGenerateQR']);
        Route::post('batch-tokens', [CustomerReviewController::class, 'generateBatchTokens']);
    });

    // Debug routes (remove in production)
    Route::get('debug/session', [SessionDebugController::class, 'debug']);
    Route::get('debug/session-test', [SessionDebugController::class, 'testSession']);
});
