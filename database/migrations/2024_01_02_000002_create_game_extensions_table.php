<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_extensions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('version')->default('1.0.0');
            $table->string('author')->nullable();
            $table->string('game_type'); // minecraft, ark, csgo, gmod, etc.
            $table->string('extension_type'); // mod, plugin, config, resource_pack
            $table->string('file_path'); // chemin du fichier uploadé
            $table->string('file_name'); // nom du fichier original
            $table->bigInteger('file_size')->default(0); // en octets
            $table->string('file_hash')->nullable(); // MD5/SHA256 pour vérification
            $table->json('metadata')->nullable(); // infos extraites du fichier
            $table->json('compatible_versions')->nullable(); // versions de jeu compatibles
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_install')->default(false); // installer auto sur new service
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->integer('download_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('game_type');
            $table->index('extension_type');
        });

        // Table pivot : extensions installées sur un service
        Schema::create('game_extension_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_extension_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'installed', 'failed', 'removed'])->default('pending');
            $table->timestamp('installed_at')->nullable();
            $table->text('install_log')->nullable();
            $table->timestamps();

            $table->unique(['game_extension_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_extension_service');
        Schema::dropIfExists('game_extensions');
    }
};
