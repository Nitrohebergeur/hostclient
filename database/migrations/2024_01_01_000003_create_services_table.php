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
            // invoice_id sans contrainte FK ici (invoices créé après)
            $table->unsignedBigInteger('invoice_id')->nullable();

            $table->string('name');
            $table->enum('status', ['pending', 'active', 'suspended', 'cancelled', 'terminated'])->default('pending');
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'semiannually', 'annually', 'biennially'])->default('monthly');

            $table->timestamp('activated_at')->nullable();
            $table->timestamp('next_due_date')->nullable();
            $table->timestamp('terminated_at')->nullable();

            $table->decimal('price', 10, 2)->default(0);
            $table->char('currency', 3)->default('EUR');

            $table->json('module_config')->nullable();
            $table->json('custom_fields')->nullable();

            $table->text('admin_notes')->nullable();
            $table->text('client_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index('next_due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
