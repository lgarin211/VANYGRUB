<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

class SessionDebugController extends Controller
{
    public function debug(Request $request)
    {
        $sessionData = [
            'session_id' => session()->getId(),
            'session_name' => session()->getName(),
            'session_started' => session()->isStarted(),
            'session_data' => session()->all(),
            'csrf_token' => csrf_token(),
            'config' => [
                'driver' => Config::get('session.driver'),
                'lifetime' => Config::get('session.lifetime'),
                'domain' => Config::get('session.domain'),
                'secure' => Config::get('session.secure'),
                'http_only' => Config::get('session.http_only'),
                'same_site' => Config::get('session.same_site'),
                'cookie' => Config::get('session.cookie'),
                'path' => Config::get('session.path'),
            ],
            'request_info' => [
                'url' => $request->url(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'is_secure' => $request->isSecure(),
                'scheme' => $request->getScheme(),
                'host' => $request->getHost(),
            ],
            'headers' => [
                'x-forwarded-proto' => $request->header('X-Forwarded-Proto'),
                'x-forwarded-for' => $request->header('X-Forwarded-For'),
                'x-forwarded-host' => $request->header('X-Forwarded-Host'),
                'cookie' => $request->header('Cookie'),
            ]
        ];

        return response()->json($sessionData);
    }

    public function testSession(Request $request)
    {
        // Test session write/read
        $testKey = 'session_test_' . time();
        $testValue = 'test_value_' . rand(1000, 9999);

        session()->put($testKey, $testValue);
        session()->save();

        $retrievedValue = session()->get($testKey);

        return response()->json([
            'test_key' => $testKey,
            'original_value' => $testValue,
            'retrieved_value' => $retrievedValue,
            'test_passed' => $testValue === $retrievedValue,
            'session_id' => session()->getId(),
            'csrf_token' => csrf_token(),
        ]);
    }
}