<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;

Route::middleware(['auth', 'role:admin|support'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Utilisateurs
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/impersonate', [UserController::class, 'impersonate'])->name('users.impersonate');
    Route::post('/users/{user}/suspend',     [UserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{user}/ban',         [UserController::class, 'ban'])->name('users.ban');

    // Rôles & Permissions
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);

    // Produits & Catégories
    Route::resource('products',   ProductController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('coupons',    \App\Http\Controllers\Admin\CouponController::class);

    // Services
    Route::resource('services', ServiceController::class)->only(['index', 'show', 'destroy']);
    Route::post('/services/{service}/suspend',  [ServiceController::class, 'suspend'])->name('services.suspend');
    Route::post('/services/{service}/activate', [ServiceController::class, 'activate'])->name('services.activate');

    // Facturation
    Route::resource('invoices', InvoiceController::class);
    Route::post('/invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
    Route::get( '/invoices/{invoice}/pdf',       [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::resource('payments', \App\Http\Controllers\Admin\PaymentController::class)->only(['index', 'show']);
    Route::resource('taxes',    \App\Http\Controllers\Admin\TaxController::class);

    // Support
    Route::resource('tickets', TicketController::class);
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    Route::post('/tickets/{ticket}/close',  [TicketController::class, 'close'])->name('tickets.close');
    Route::resource('announcements', \App\Http\Controllers\Admin\AnnouncementController::class);

    // Domaines
    Route::resource('domains', \App\Http\Controllers\Admin\DomainController::class)->only(['index', 'show']);

    // Système
    Route::get('/plugins',  fn() => view('admin.plugins.index'))->name('plugins.index');
    Route::get('/themes',   fn() => view('admin.themes.index'))->name('themes.index');
    Route::get('/api',      fn() => view('admin.api.index'))->name('api.index');
    Route::get('/logs',     fn() => view('admin.logs.index'))->name('logs.index');
    Route::get('/backups',  fn() => view('admin.backups.index'))->name('backups.index');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

});
