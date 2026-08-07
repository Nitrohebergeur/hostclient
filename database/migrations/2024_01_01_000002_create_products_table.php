<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Catégories de produits
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Produits / Offres
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('product_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type')->default('hosting'); // hosting, vps, dedicated, game, domain, custom
            $table->string('module')->nullable();        // cpanel, pterodactyl, proxmox, etc.

            // Tarifs
            $table->decimal('price_monthly', 10, 2)->default(0);
            $table->decimal('price_quarterly', 10, 2)->default(0);
            $table->decimal('price_semiannually', 10, 2)->default(0);
            $table->decimal('price_annually', 10, 2)->default(0);
            $table->decimal('price_biennially', 10, 2)->default(0);
            $table->decimal('setup_fee', 10, 2)->default(0);
            $table->char('currency', 3)->default('EUR');

            // Ressources
            $table->json('resources')->nullable(); // { disk, bandwidth, databases, emails, ... }
            $table->json('config_options')->nullable();

            // Paramètres
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('auto_provision')->default(false);
            $table->integer('stock')->nullable(); // null = illimité

            $table->timestamps();
            $table->softDeletes();
        });

        // Groupes de produits (pour affichage)
        Schema::create('product_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_product_group', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_group_id')->constrained()->onDelete('cascade');
            $table->primary(['product_id', 'product_group_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_product_group');
        Schema::dropIfExists('product_groups');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
    }
};
