<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class Extension extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'version', 'description', 'author', 'author_url',
        'type', 'file_path', 'file_name', 'file_size', 'file_hash',
        'manifest', 'config_schema', 'config_values',
        'is_active', 'is_built_in', 'uploaded_by',
    ];

    protected $casts = [
        'manifest' => 'array',
        'config_schema' => 'array',
        'is_active' => 'boolean',
        'is_built_in' => 'boolean',
        'file_size' => 'integer',
    ];

    /** Types disponibles */
    public static array $types = [
        'server_module'   => 'Module Serveur (cPanel, Plesk, Docker…)',
        'theme'           => 'Thème d\'interface',
        'payment_gateway' => 'Passerelle de paiement',
        'provisioner'     => 'Module de provisionnement',
        'custom'          => 'Extension personnalisée',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Config chiffrée
    public function setConfigValuesAttribute($value): void
    {
        if ($value && is_array($value)) {
            $this->attributes['config_values'] = Crypt::encryptString(json_encode($value));
        }
    }

    public function getConfigValuesAttribute($value): ?array
    {
        if (!$value) return null;
        try {
            return json_decode(Crypt::decryptString($value), true);
        } catch (\Exception) {
            return null;
        }
    }

    public function getHumanFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        foreach (['B', 'KB', 'MB', 'GB'] as $unit) {
            if ($bytes < 1024) return round($bytes, 1) . ' ' . $unit;
            $bytes /= 1024;
        }
        return round($bytes, 1) . ' TB';
    }

    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    public function deleteFile(): void
    {
        if ($this->file_path && Storage::disk('local')->exists($this->file_path)) {
            Storage::disk('local')->delete($this->file_path);
        }
    }
}
