<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GameExtension extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'version',
        'author',
        'game_type',
        'extension_type',
        'file_path',
        'file_name',
        'file_size',
        'file_hash',
        'metadata',
        'compatible_versions',
        'is_active',
        'auto_install',
        'uploaded_by',
        'download_count',
    ];

    protected $casts = [
        'metadata' => 'array',
        'compatible_versions' => 'array',
        'is_active' => 'boolean',
        'auto_install' => 'boolean',
        'file_size' => 'integer',
        'download_count' => 'integer',
    ];

    /**
     * Types de jeux supportés
     */
    public static array $gameTypes = [
        'minecraft' => 'Minecraft',
        'ark' => 'ARK: Survival Evolved',
        'csgo' => 'CS:GO / CS2',
        'gmod' => 'Garry\'s Mod',
        'rust' => 'Rust',
        'terraria' => 'Terraria',
        'valheim' => 'Valheim',
        'satisfactory' => 'Satisfactory',
        'palworld' => 'Palworld',
        'project_zomboid' => 'Project Zomboid',
        'fivem' => 'FiveM (GTA V)',
        'custom' => 'Custom / Other',
    ];

    /**
     * Types d'extensions supportés
     */
    public static array $extensionTypes = [
        'mod' => 'Mod',
        'plugin' => 'Plugin',
        'config' => 'Configuration',
        'resource_pack' => 'Resource Pack',
        'map' => 'Map',
        'script' => 'Script',
        'other' => 'Other',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'game_extension_service')
            ->withPivot(['status', 'installed_at', 'install_log'])
            ->withTimestamps();
    }

    /**
     * Taille humainement lisible
     */
    public function getHumanFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Label du type de jeu
     */
    public function getGameTypeLabel(): string
    {
        return self::$gameTypes[$this->game_type] ?? ucfirst($this->game_type);
    }

    /**
     * Label du type d'extension
     */
    public function getExtensionTypeLabel(): string
    {
        return self::$extensionTypes[$this->extension_type] ?? ucfirst($this->extension_type);
    }

    /**
     * Incrémenter le compteur de téléchargements
     */
    public function incrementDownloads(): void
    {
        $this->increment('download_count');
    }
}
