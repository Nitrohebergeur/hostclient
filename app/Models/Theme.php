<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Theme extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'version', 'description', 'author',
        'preview_image', 'type', 'file_path', 'config',
        'is_active', 'is_built_in', 'uploaded_by',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
        'is_built_in' => 'boolean',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Activer ce thème (désactive les autres du même type)
     */
    public function activate(): void
    {
        // Désactiver les autres thèmes du même type
        static::where('type', $this->type)
            ->where('id', '!=', $this->id)
            ->update(['is_active' => false]);

        $this->update(['is_active' => true]);

        // Sauvegarder dans les settings
        SystemSetting::set("active_theme_{$this->type}", $this->slug, 'string', 'appearance');
    }

    public function getPreviewUrlAttribute(): string
    {
        if ($this->preview_image) {
            return asset('storage/' . $this->preview_image);
        }
        return asset('images/theme-placeholder.png');
    }

    public function deleteFile(): void
    {
        if ($this->file_path && Storage::disk('local')->exists($this->file_path)) {
            Storage::disk('local')->delete($this->file_path);
        }
    }
}
