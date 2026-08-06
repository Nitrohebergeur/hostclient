<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('provider');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(false);
            $table->json('config')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('payment_gateway_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['payment', 'refund', 'credit', 'debit'])->default('payment');
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled', 'refunded'])->default('pending');
            $table->decimal('amount', 10, 2);
            $table->decimal('fee', 10, 2)->default(0);
            $table->string('currency', 3)->default('EUR');
            $table->string('payment_method')->nullable();
            $table->string('external_id')->nullable();
            $table->json('metadata')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('payment_gateways');
    }
};
