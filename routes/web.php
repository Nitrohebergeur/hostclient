<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\ServiceController;
use App\Http\Controllers\Client\InvoiceController;
use App\Http\Controllers\Client\TicketController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\SecurityController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\HomeController;

// ─── Page d'accueil ─────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [HomeController::class, 'products'])->name('products');
Route::get('/products/{categorySlug}', [HomeController::class, 'products'])->name('products.category');
Route::get('/order/{slug}', [HomeController::class, 'productDetail'])->name('product.detail');

// ─── Authentification ────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/password/reset', fn() => view('auth.passwords.email'))->name('password.request');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ─── Espace Client ───────────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Services
    Route::prefix('services')->name('services.')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('/{service}', [ServiceController::class, 'show'])->name('show');
        Route::post('/{service}/renew', [ServiceController::class, 'renew'])->name('renew');
        Route::post('/{service}/suspend', [ServiceController::class, 'suspend'])->name('suspend');
        Route::delete('/{service}', [ServiceController::class, 'cancel'])->name('cancel');
    });

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/new', fn() => view('client.orders.create'))->name('create');
        Route::get('/', fn() => view('client.orders.index'))->name('index');
    });

    // Invoices
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('pdf');
        Route::get('/{invoice}/pay', [InvoiceController::class, 'pay'])->name('pay');
        Route::post('/{invoice}/pay', [InvoiceController::class, 'processPayment'])->name('pay.process');
    });

    // Support Tickets
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('/create', [TicketController::class, 'create'])->name('create');
        Route::post('/', [TicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [TicketController::class, 'show'])->name('show');
        Route::post('/{ticket}/reply', [TicketController::class, 'reply'])->name('reply');
        Route::post('/{ticket}/close', [TicketController::class, 'close'])->name('close');
    });

    // Domains
    Route::prefix('domains')->name('domains.')->group(function () {
        Route::get('/', fn() => view('client.domains.index'))->name('index');
    });

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/address', [ProfileController::class, 'updateAddress'])->name('address');
        Route::put('/preferences', [ProfileController::class, 'updatePreferences'])->name('preferences');
    });

    // Security
    Route::prefix('security')->name('security.')->group(function () {
        Route::get('/', [SecurityController::class, 'index'])->name('index');
        Route::put('/password', [SecurityController::class, 'updatePassword'])->name('password');
        Route::post('/two-factor', [SecurityController::class, 'enableTwoFactor'])->name('two-factor.enable');
        Route::delete('/two-factor', [SecurityController::class, 'disableTwoFactor'])->name('two-factor.disable');
        Route::get('/api-keys', [SecurityController::class, 'apiKeys'])->name('api-keys');
        Route::post('/api-keys', [SecurityController::class, 'createApiKey'])->name('api-keys.create');
        Route::delete('/api-keys/{key}', [SecurityController::class, 'revokeApiKey'])->name('api-keys.revoke');
        Route::delete('/sessions', [SecurityController::class, 'destroyOtherSessions'])->name('sessions.destroy');
    });

});

// ─── Admin Panel ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    // Les routes admin seront ajoutées dans la prochaine étape
});

// ─── Installateur ─────────────────────────────────────────────────────────────
Route::get('/install', fn() => view('install.index'))->name('install');
