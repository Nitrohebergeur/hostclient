<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;

Route::prefix('v1')->name('api.v1.')->group(function () {
    
    // Authentication
    Route::post('/auth/login', [AuthController::class, 'login'])->name('login');
    Route::post('/auth/register', [AuthController::class, 'register'])->name('register');
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/auth/user', [AuthController::class, 'user'])->name('user');
        
        // Services
        Route::apiResource('services', ServiceController::class);
        Route::post('services/{service}/renew', [ServiceController::class, 'renew'])->name('services.renew');
        Route::post('services/{service}/cancel', [ServiceController::class, 'cancel'])->name('services.cancel');
        
        // Invoices
        Route::apiResource('invoices', InvoiceController::class)->only(['index', 'show']);
        Route::post('invoices/{invoice}/pay', [InvoiceController::class, 'pay'])->name('invoices.pay');
        Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
        
        // Tickets
        Route::apiResource('tickets', TicketController::class);
        Route::post('tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
        Route::post('tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');
        
        // Orders
        Route::apiResource('orders', OrderController::class);
        
        // Products (public but rate limited)
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
    });
});
