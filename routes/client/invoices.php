<?php

/*
 * This file is part of the Hostclient project.
 * It is the property of the Hostclient association.
 *
 * Personal and non-commercial use of this source code is permitted.
 * However, any use in a project that generates profit (directly or indirectly),
 * or any reuse for commercial purposes, requires prior authorization from Hostclient.
 *
 * To request permission or for more information, please contact our support:
 * https://Hostclient.com/client/support
 *
 * Learn more about Hostclient License at:
 * https://Hostclient.com/eula
 *
 * Year: 2025
 */
use App\Http\Controllers\Front\Billing\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('/client')->name('front.')->group(function () {
    Route::prefix('/invoices')->name('invoices')->middleware(['auth'])->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('.index');
        Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('.show');
        Route::get('/{invoice}/download', [InvoiceController::class, 'download'])->name('.download');
        Route::get('/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('.pdf');
        Route::get('/{invoice}/pay/{gateway}', [InvoiceController::class, 'pay'])->name('.pay');
        Route::post('/{invoice}/balance', [InvoiceController::class, 'balance'])->name('.balance');
    });
});
