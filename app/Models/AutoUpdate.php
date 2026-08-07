<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'version',
        'commit_hash',
        'branch',
        'changelog',
        'status',
        'started_at',
        'completed_at',
        'backup_data',
        'error_message',
        'auto_applied',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'backup_data' => 'array',
        'auto_applied' => 'boolean',
    ];

    /**
     * Marquer la mise à jour comme en cours
     */
    public function markAsInProgress(): void
    {
        $this->update([
            'status' => 'installing',
            'started_at' => now(),
        ]);
    }

    /**
     * Marquer la mise à jour comme complétée
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Marquer la mise à jour comme échouée
     */
    public function markAsFailed(string $error): void
    {
        $this->update([
            'status' => 'failed',
            'completed_at' => now(),
            'error_message' => $error,
        ]);
    }
}
