<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/status', function () {
    return response()->json(['status' => 'API is running']);
});
Route::get('/products',
    [ProductApiController::class, 'index']

);

Route::post('/register', [AuthApiController::class, 'store']);
Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->get('/all', function (Request $request) {
    return $request->user();
});

Route::prefix('cart')->middleware('auth:sanctum')->group(function () {
    Route::post('/add', [CartController::class, 'addToCart']);
    Route::get('/get', [CartController::class, 'getCart']);
    Route::delete('/remove/{id}', [CartController::class, 'remove']);
    Route::post('/update', [CartController::class, 'incORdecCart']);

});

Route::prefix('/order')->middleware('auth:sanctum')->group(function () {
    Route::get('/history', [OrderController::class, 'getMyOrderHistory']);
    Route::post('/payment-confirm', [OrderController::class, 'proceedOrder']);
    Route::post('/checkout', [OrderController::class, 'checkout']);

});

Route::middleware('auth:sanctum')->prefix('notifications')->group(function () {

    Route::get('/', [NotificationController::class, 'index']);
    Route::patch('/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead']);

});
