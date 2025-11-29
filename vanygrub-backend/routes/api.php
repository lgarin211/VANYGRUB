<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DataController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\HeroSectionController;

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

// VanyGrub API Routes with prefix /vny/
Route::prefix('vny')->group(function () {
    // Data endpoints for Next.js constants
    Route::get('data', [DataController::class, 'getAllData']);
    Route::get('home-data', [DataController::class, 'getHomeData']);

    // Categories API
    Route::apiResource('categories', CategoryController::class);

    // Products API
    Route::apiResource('products', ProductController::class);
    Route::get('products/slug/{slug}', [ProductController::class, 'getBySlug']);
    Route::get('featured-products', [ProductController::class, 'getFeatured']);

    // Hero Sections API
    Route::apiResource('hero-sections', HeroSectionController::class);
});
