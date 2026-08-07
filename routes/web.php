<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Client\DashboardController as ClientDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featured = \App\Models\Product::active()->inStock()->featured()->with('category')->take(6)->get();
    $categories = \App\Models\ProductCategory::active()
        ->with(['products' => fn($q) => $q->active()->inStock()->orderBy('sort_order')->take(3)])
        ->orderBy('sort_order')
        ->get();
    return view('welcome', compact('featured', 'categories'));
})->name('home');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
});

Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('client.dashboard');
    })->name('dashboard');
});

// Client routes
Route::prefix('client')->name('client.')->middleware(['auth', 'role:client'])->group(function () {
    Route::get('/dashboard', [ClientDashboard::class, 'index'])->name('dashboard');
    
    // Services
    Route::resource('services', \App\Http\Controllers\Client\ServiceController::class);
    
    // Orders
    Route::resource('orders', \App\Http\Controllers\Client\OrderController::class);
    
    // Invoices
    Route::get('invoices', [\App\Http\Controllers\Client\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{invoice}', [\App\Http\Controllers\Client\InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('invoices/{invoice}/download', [\App\Http\Controllers\Client\InvoiceController::class, 'download'])->name('invoices.download');
    Route::post('invoices/{invoice}/pay', [\App\Http\Controllers\Client\InvoiceController::class, 'pay'])->name('invoices.pay');
    
    // Tickets
    Route::resource('tickets', \App\Http\Controllers\Client\TicketController::class);
    Route::post('tickets/{ticket}/reply', [\App\Http\Controllers\Client\TicketController::class, 'reply'])->name('tickets.reply');
    Route::post('tickets/{ticket}/close', [\App\Http\Controllers\Client\TicketController::class, 'close'])->name('tickets.close');
    
    // Profile
    Route::get('profile', [\App\Http\Controllers\Client\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [\App\Http\Controllers\Client\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [\App\Http\Controllers\Client\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('profile', [\App\Http\Controllers\Client\ProfileController::class, 'destroy'])->name('profile.destroy');

    // API Keys
    Route::resource('api-keys', \App\Http\Controllers\Client\ApiKeyController::class);
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // Clients
    Route::resource('clients', \App\Http\Controllers\Admin\ClientController::class);
    
    // Products
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    
    // Services
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
    Route::post('services/{service}/activate', [\App\Http\Controllers\Admin\ServiceController::class, 'activate'])->name('services.activate');
    Route::post('services/{service}/suspend', [\App\Http\Controllers\Admin\ServiceController::class, 'suspend'])->name('services.suspend');
    Route::post('services/{service}/terminate', [\App\Http\Controllers\Admin\ServiceController::class, 'terminate'])->name('services.terminate');
    
    // Orders
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    
    // Invoices
    Route::resource('invoices', \App\Http\Controllers\Admin\InvoiceController::class);
    
    // Tickets
    Route::resource('tickets', \App\Http\Controllers\Admin\TicketController::class);
    Route::post('tickets/{ticket}/assign', [\App\Http\Controllers\Admin\TicketController::class, 'assign'])->name('tickets.assign');
    
    // Transactions
    Route::resource('transactions', \App\Http\Controllers\Admin\TransactionController::class);
    
    // Payment Gateways
    Route::resource('payment-gateways', \App\Http\Controllers\Admin\PaymentGatewayController::class);
    
    // Coupons
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
    
    // Settings
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    
    // Homepage Customization
    Route::get('homepage/edit', [\App\Http\Controllers\Admin\HomePageController::class, 'edit'])->name('homepage.edit');
    Route::put('homepage/update', [\App\Http\Controllers\Admin\HomePageController::class, 'update'])->name('homepage.update');
    Route::get('homepage/preview', [\App\Http\Controllers\Admin\HomePageController::class, 'preview'])->name('homepage.preview');
    
    // Modules
    Route::get('modules', [\App\Http\Controllers\Admin\ModuleController::class, 'index'])->name('modules.index');
    Route::post('modules/{module}/install', [\App\Http\Controllers\Admin\ModuleController::class, 'install'])->name('modules.install');
    Route::post('modules/{module}/uninstall', [\App\Http\Controllers\Admin\ModuleController::class, 'uninstall'])->name('modules.uninstall');
    Route::post('modules/{module}/toggle', [\App\Http\Controllers\Admin\ModuleController::class, 'toggle'])->name('modules.toggle');
    
    // Users & Permissions
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
    
    // Activity Log
    Route::get('activity', [\App\Http\Controllers\Admin\ActivityController::class, 'index'])->name('activity.index');
});

// Store routes (public)
Route::prefix('store')->name('store.')->group(function () {
    Route::get('/', [\App\Http\Controllers\StoreController::class, 'index'])->name('index');
    Route::get('/{category}', [\App\Http\Controllers\StoreController::class, 'category'])->name('category');
    Route::get('/{category}/{product}', [\App\Http\Controllers\StoreController::class, 'product'])->name('product');
    Route::post('/cart/add', [\App\Http\Controllers\StoreController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [\App\Http\Controllers\StoreController::class, 'cart'])->name('cart');
    Route::post('/checkout', [\App\Http\Controllers\StoreController::class, 'checkout'])->name('checkout');
});

// Page /offres : liste publique des produits créés par l'admin
Route::get('/offres', [\App\Http\Controllers\PublicOfferController::class, 'index'])->name('offers');

// Payment webhook routes
Route::post('/webhooks/stripe', [\App\Http\Controllers\WebhookController::class, 'stripe'])->name('webhooks.stripe');
Route::post('/webhooks/paypal', [\App\Http\Controllers\WebhookController::class, 'paypal'])->name('webhooks.paypal');
Route::post('/webhooks/mollie', [\App\Http\Controllers\WebhookController::class, 'mollie'])->name('webhooks.mollie');

// Checkout return URLs (PayPal redirects here after user approves)
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout/success/{order}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel/{order}', [\App\Http\Controllers\CheckoutController::class, 'cancel'])->name('checkout.cancel');
    Route::get('/checkout/confirmation/{order}', [\App\Http\Controllers\CheckoutController::class, 'successPage'])->name('checkout.success.page');
});
