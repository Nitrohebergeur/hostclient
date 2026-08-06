<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('product_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['hosting', 'vps', 'dedicated', 'minecraft', 'fivem', 'rust', 'discord_bot', 'database', 'domain', 'custom'])->default('custom');
            $table->decimal('price', 10, 2);
            $table->decimal('setup_fee', 10, 2)->default(0);
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'semi_annually', 'annually', 'biennially', 'triennially', 'one_time'])->default('monthly');
            $table->integer('stock')->nullable();
            $table->boolean('is_unlimited_stock')->default(true);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('auto_setup')->default(false);
            $table->string('module')->nullable();
            $table->json('config')->nullable();
            $table->json('features')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
    }
};
