<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ServerController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\AutoUpdateController;
use App\Http\Controllers\Admin\GameExtensionController;
use App\Http\Controllers\Admin\ExtensionController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\SettingController;

Route::middleware(['auth', 'role:admin|support'])->prefix('admin')->name('admin.')->group(function () {

    // ── Dashboard ──────────────────────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Utilisateurs ───────────────────────────────────────────────────────
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/impersonate', [UserController::class, 'impersonate'])->name('users.impersonate');
    Route::post('/users/{user}/suspend',     [UserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{user}/ban',         [UserController::class, 'ban'])->name('users.ban');

    // Rôles & Permissions
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);

    // ── Produits ───────────────────────────────────────────────────────────
    Route::post('/products/{product}/duplicate', [ProductController::class, 'duplicate'])->name('products.duplicate');
    Route::resource('products', ProductController::class);

    // Catégories de produits
    Route::resource('products/categories', ProductCategoryController::class)->names([
        'index'   => 'products.categories.index',
        'create'  => 'products.categories.create',
        'store'   => 'products.categories.store',
        'edit'    => 'products.categories.edit',
        'update'  => 'products.categories.update',
        'destroy' => 'products.categories.destroy',
    ]);

    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);

    // ── Serveurs de provisionnement ────────────────────────────────────────
    Route::post('/servers/{server}/test', [ServerController::class, 'testConnection'])->name('servers.test');
    Route::resource('servers', ServerController::class);

    // ── Services ───────────────────────────────────────────────────────────
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class)->only(['index', 'show', 'destroy']);
    Route::post('/services/{service}/suspend',  [\App\Http\Controllers\Admin\ServiceController::class, 'suspend'])->name('services.suspend');
    Route::post('/services/{service}/activate', [\App\Http\Controllers\Admin\ServiceController::class, 'activate'])->name('services.activate');
    Route::post('/services/{service}/terminate',[\App\Http\Controllers\Admin\ServiceController::class, 'terminate'])->name('services.terminate');

    // ── Facturation ────────────────────────────────────────────────────────
    Route::resource('invoices', InvoiceController::class);
    Route::post('/invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
    Route::get( '/invoices/{invoice}/pdf',       [InvoiceController::class, 'pdf'])->name('invoices.pdf');

    Route::resource('payments', \App\Http\Controllers\Admin\PaymentController::class)->only(['index', 'show']);
    Route::resource('taxes',    \App\Http\Controllers\Admin\TaxController::class);

    // Passerelles de paiement
    Route::post('/payment-gateways/{paymentGateway}/toggle', [PaymentGatewayController::class, 'toggle'])->name('payment-gateways.toggle');
    Route::resource('payment-gateways', PaymentGatewayController::class);

    // Devises
    Route::post('/currencies/{currency}/set-default', [CurrencyController::class, 'setDefault'])->name('currencies.set-default');
    Route::post('/currencies/update-rates', [CurrencyController::class, 'updateRates'])->name('currencies.update-rates');
    Route::resource('currencies', CurrencyController::class)->except(['show']);

    // ── Support ────────────────────────────────────────────────────────────
    Route::resource('tickets', TicketController::class);
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    Route::post('/tickets/{ticket}/close',  [TicketController::class, 'close'])->name('tickets.close');
    Route::resource('announcements', \App\Http\Controllers\Admin\AnnouncementController::class);

    // ── Domaines ───────────────────────────────────────────────────────────
    Route::resource('domains', \App\Http\Controllers\Admin\DomainController::class)->only(['index', 'show']);

    // ── Extensions (modules serveur, passerelles, etc.) ───────────────────
    Route::post('/extensions/{extension}/toggle', [ExtensionController::class, 'toggle'])->name('extensions.toggle');
    Route::resource('extensions', ExtensionController::class);

    // Extensions Jeux
    Route::get('/game-extensions/{gameExtension}/download', [GameExtensionController::class, 'download'])->name('game-extensions.download');
    Route::post('/game-extensions/{gameExtension}/toggle', [GameExtensionController::class, 'toggle'])->name('game-extensions.toggle');
    Route::resource('game-extensions', GameExtensionController::class);

    // ── Thèmes ─────────────────────────────────────────────────────────────
    Route::post('/themes/{theme}/activate', [ThemeController::class, 'activate'])->name('themes.activate');
    Route::resource('themes', ThemeController::class)->except(['edit', 'update', 'show']);

    // ── Auto-update ────────────────────────────────────────────────────────
    Route::prefix('auto-updates')->name('auto-updates.')->group(function () {
        Route::get('/',                               [AutoUpdateController::class, 'index'])->name('index');
        Route::post('/check',                         [AutoUpdateController::class, 'checkForUpdates'])->name('check');
        Route::post('/download',                      [AutoUpdateController::class, 'download'])->name('download');
        Route::post('/{update}/install',              [AutoUpdateController::class, 'install'])->name('install');
        Route::post('/{update}/rollback',             [AutoUpdateController::class, 'rollback'])->name('rollback');
        Route::put('/settings',                       [AutoUpdateController::class, 'updateSettings'])->name('settings');
    });

    // ── Système ────────────────────────────────────────────────────────────
    Route::get('/api',     fn() => view('admin.api.index'))->name('api.index');
    Route::get('/logs',    fn() => view('admin.logs.index'))->name('logs.index');
    Route::get('/backups', fn() => view('admin.backups.index'))->name('backups.index');
    Route::post('/backups', fn() => back()->with('success', 'Sauvegarde lancée.'))->name('backups.run');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

});
