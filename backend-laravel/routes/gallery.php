<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GalleryController;

/*
|--------------------------------------------------------------------------
| Gallery API Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'gallery', 'as' => 'gallery.'], function () {
    
    // Public routes (no authentication required)
    Route::get('/', [GalleryController::class, 'index'])->name('index');
    Route::get('/{id}', [GalleryController::class, 'show'])->name('show');
    
    // Protected routes (require authentication)
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/', [GalleryController::class, 'store'])->name('store');
        Route::post('/bulk-upload', [GalleryController::class, 'bulkUpload'])->name('bulk-upload');
        Route::put('/{id}', [GalleryController::class, 'update'])->name('update');
        Route::delete('/{id}', [GalleryController::class, 'destroy'])->name('destroy');
    });
    
});