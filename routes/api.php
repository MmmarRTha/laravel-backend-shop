<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::apiResource('/orders', OrderController::class);
    Route::apiResource('/products', ProductController::class);
    Route::apiResource('/categories', CategoryController::class);
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Health check endpoint for Render
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

// Debug endpoint for registration testing
Route::post('/debug/register', function (Request $request) {
    return response()->json([
        'received_data' => $request->all(),
        'headers' => $request->headers->all(),
        'content_type' => $request->header('Content-Type'),
        'method' => $request->method()
    ]);
});

// Log viewer endpoint (remove in production!)
Route::get('/debug/logs', function () {
    $logFile = storage_path('logs/laravel.log');
    
    if (file_exists($logFile)) {
        $logs = file_get_contents($logFile);
        $recentLogs = implode("\n", array_slice(explode("\n", $logs), -100)); // Last 100 lines
        
        return response($recentLogs, 200, [
            'Content-Type' => 'text/plain'
        ]);
    }
    
    return response('No log file found', 404);
});

// Test logging endpoint
Route::get('/debug/test-log', function () {
    \Log::info('Test log entry from debug endpoint');
    \Log::error('Test error log entry');
    \Log::warning('Test warning log entry');
    
    return response()->json([
        'message' => 'Test logs written',
        'timestamp' => now()
    ]);
});
