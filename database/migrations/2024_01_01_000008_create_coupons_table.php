<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed', 'free_setup'])->default('percentage');
            $table->decimal('value', 10, 2);
            $table->integer('max_uses')->nullable();
            $table->integer('uses')->default(0);
            $table->integer('max_uses_per_user')->default(1);
            $table->decimal('minimum_order', 10, 2)->nullable();
            $table->date('starts_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('apply_to_setup_fee')->default(false);
            $table->json('product_ids')->nullable();
            $table->json('category_ids')->nullable();
            $table->timestamps();
        });

        Schema::create('coupon_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->decimal('discount_amount', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_usage');
        Schema::dropIfExists('coupons');
    }
};
