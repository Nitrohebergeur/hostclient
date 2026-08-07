<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter tarification horaire aux produits
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price_hourly', 10, 4)->default(0)->after('type');
            $table->boolean('allow_hourly_billing')->default(false)->after('price_hourly');
        });

        // Ajouter cycle horaire aux services
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('billing_cycle');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->enum('billing_cycle', ['hourly', 'monthly', 'quarterly', 'semiannually', 'annually', 'biennially'])->default('monthly')->after('status');
        });

        // Table de configuration des passerelles de paiement
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Stripe, PayPal, Mollie, etc.
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(false);
            $table->integer('order')->default(0);
            
            // Configuration
            $table->json('config')->nullable(); // Clés API, secrets, webhooks
            $table->json('supported_currencies')->nullable();
            $table->decimal('fee_fixed', 10, 2)->default(0);
            $table->decimal('fee_percentage', 5, 2)->default(0);
            
            // Fonctionnalités supportées
            $table->boolean('supports_recurring')->default(false);
            $table->boolean('supports_refunds')->default(false);
            $table->boolean('supports_webhooks')->default(false);
            
            $table->timestamps();
        });

        // Table des serveurs de provisionnement
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // pterodactyl, cpanel, plesk, proxmox, docker, custom
            $table->string('hostname');
            $table->integer('port')->default(443);
            $table->boolean('use_ssl')->default(true);
            
            // Authentification
            $table->text('api_key')->nullable();
            $table->text('api_secret')->nullable();
            $table->string('username')->nullable();
            $table->text('password')->nullable();
            
            // Configuration spécifique
            $table->json('config')->nullable();
            
            // Statut et limites
            $table->boolean('is_active')->default(true);
            $table->integer('max_accounts')->nullable(); // null = illimité
            $table->integer('current_accounts')->default(0);
            
            // Monitoring
            $table->enum('status', ['online', 'offline', 'maintenance'])->default('online');
            $table->timestamp('last_checked_at')->nullable();
            $table->json('last_check_data')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Lier les produits aux serveurs
        Schema::create('product_server', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('server_id')->constrained()->onDelete('cascade');
            $table->integer('priority')->default(0); // Pour load balancing
            $table->primary(['product_id', 'server_id']);
        });

        // Mettre à jour la table services pour ajouter la référence au serveur
        Schema::table('services', function (Blueprint $table) {
            $table->foreignId('server_id')->nullable()->after('product_id')->constrained()->onDelete('set null');
        });

        // Table pour les mises à jour automatiques
        Schema::create('auto_updates', function (Blueprint $table) {
            $table->id();
            $table->string('version');
            $table->string('commit_hash');
            $table->string('branch')->default('main');
            $table->text('changelog')->nullable();
            $table->enum('status', ['pending', 'downloading', 'installing', 'completed', 'failed', 'rolled_back'])->default('pending');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->json('backup_data')->nullable(); // Informations de sauvegarde avant mise à jour
            $table->text('error_message')->nullable();
            $table->boolean('auto_applied')->default(false);
            $table->timestamps();
        });

        // Configuration système pour auto-update
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json
            $table->string('group')->nullable(); // general, updates, billing, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('auto_updates');
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['server_id']);
            $table->dropColumn('server_id');
        });
        Schema::dropIfExists('product_server');
        Schema::dropIfExists('servers');
        Schema::dropIfExists('payment_gateways');
        
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('billing_cycle');
        });
        Schema::table('services', function (Blueprint $table) {
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'semiannually', 'annually', 'biennially'])->default('monthly');
        });
        
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['price_hourly', 'allow_hourly_billing']);
        });
    }
};
