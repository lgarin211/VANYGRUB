<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

// Order invoice route
Route::get('/orders/{order}/invoice', [\App\Http\Controllers\OrderController::class, 'invoice'])
    ->name('orders.invoice');

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
