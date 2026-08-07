<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Extensions (plugins : modules serveur, thèmes, etc.) ──────────
        Schema::create('extensions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('version')->default('1.0.0');
            $table->text('description')->nullable();
            $table->string('author')->nullable();
            $table->string('author_url')->nullable();
            $table->string('type'); // server_module | theme | payment_gateway | provisioner | custom
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->bigInteger('file_size')->default(0);
            $table->string('file_hash')->nullable();
            $table->json('manifest')->nullable(); // composer.json-like metadata
            $table->json('config_schema')->nullable(); // fields the admin must fill
            $table->json('config_values')->nullable(); // stored config values (encrypted)
            $table->boolean('is_active')->default(false);
            $table->boolean('is_built_in')->default(false); // livré avec le système
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            $table->index('type');
        });

        // ── Thèmes ────────────────────────────────────────────────────────
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('version')->default('1.0.0');
            $table->text('description')->nullable();
            $table->string('author')->nullable();
            $table->string('preview_image')->nullable();
            $table->string('type')->default('client'); // client | admin | both
            $table->string('file_path')->nullable(); // zip uploadé
            $table->json('config')->nullable(); // couleurs, fonts, etc.
            $table->boolean('is_active')->default(false);
            $table->boolean('is_built_in')->default(false);
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // ── Devises ───────────────────────────────────────────────────────
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->char('code', 3)->unique(); // EUR, USD, GBP…
            $table->string('name'); // Euro, US Dollar…
            $table->string('symbol', 10); // €, $, £…
            $table->string('symbol_position')->default('left'); // left | right
            $table->integer('decimal_places')->default(2);
            $table->string('decimal_separator')->default('.');
            $table->string('thousands_separator')->default(',');
            $table->decimal('exchange_rate', 12, 6)->default(1.000000); // vs devise de base
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('rate_updated_at')->nullable();
            $table->timestamps();
        });

        // ── Renouvellements automatiques ─────────────────────────────────
        Schema::create('auto_renewals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('enabled')->default(true);
            $table->string('payment_gateway')->nullable(); // gateway préféré
            $table->integer('days_before_renewal')->default(7); // créer facture X jours avant
            $table->integer('retry_attempts')->default(3);
            $table->integer('current_retries')->default(0);
            $table->timestamp('last_attempted_at')->nullable();
            $table->timestamp('next_attempt_at')->nullable();
            $table->timestamps();
            $table->unique('service_id');
        });

        // ── Rappels de paiement ───────────────────────────────────────────
        Schema::create('payment_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('days_before_due'); // -1 = déjà dû
            $table->string('channel')->default('email'); // email | sms
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        // ── Page produits publique (slug configurable) ────────────────────
        Schema::create('storefront_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content')->nullable(); // HTML ou Markdown
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->boolean('is_published')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storefront_pages');
        Schema::dropIfExists('payment_reminders');
        Schema::dropIfExists('auto_renewals');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('themes');
        Schema::dropIfExists('extensions');
    }
};
