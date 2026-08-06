<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('identifier')->unique()->nullable();
            $table->enum('status', ['pending', 'active', 'suspended', 'terminated', 'cancelled'])->default('pending');
            $table->decimal('price', 10, 2);
            $table->decimal('setup_fee', 10, 2)->default(0);
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'semi_annually', 'annually', 'biennially', 'triennially', 'one_time'])->default('monthly');
            $table->date('next_due_date')->nullable();
            $table->date('next_invoice_date')->nullable();
            $table->boolean('auto_renew')->default(true);
            $table->json('config')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->timestamp('terminated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('service_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('action');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_history');
        Schema::dropIfExists('services');
    }
};
