<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            // Profil
            $table->string('company')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('website')->nullable();
            $table->string('avatar')->nullable();

            // Adresse
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->char('country', 2)->default('FR');
            $table->string('vat_number', 30)->nullable();

            // Préférences
            $table->char('language', 5)->default('fr');
            $table->char('currency', 3)->default('EUR');
            $table->json('notification_preferences')->nullable();

            // Sécurité
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();

            // Financier
            $table->decimal('credit_balance', 10, 2)->default(0.00);

            // Statut
            $table->enum('status', ['active', 'suspended', 'banned'])->default('active');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
