<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'category_id',
        'assigned_to',
        'service_id',
        'subject',
        'priority',
        'status',
        'last_reply_at',
        'last_reply_by',
        'closed_at',
    ];

    protected $casts = [
        'last_reply_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }

    public function lastReplyBy()
    {
        return $this->belongsTo(User::class, 'last_reply_by');
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress', 'waiting_customer', 'waiting_staff']);
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }

    // Methods
    public function close(): void
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);
    }

    public function reopen(): void
    {
        $this->update([
            'status' => 'open',
            'closed_at' => null,
        ]);
    }

    public function assignTo(int $userId): void
    {
        $this->update([
            'assigned_to' => $userId,
            'status' => 'in_progress',
        ]);
    }

    public function addReply(string $message, bool $isInternal = false, bool $isStaff = false): TicketReply
    {
        $reply = $this->replies()->create([
            'user_id' => auth()->id(),
            'message' => $message,
            'is_internal' => $isInternal,
            'is_staff' => $isStaff,
        ]);

        $this->update([
            'last_reply_at' => now(),
            'last_reply_by' => auth()->id(),
            'status' => $isStaff ? 'waiting_customer' : 'waiting_staff',
        ]);

        return $reply;
    }

    public function isOpen(): bool
    {
        return !in_array($this->status, ['closed']);
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    public static function generateTicketNumber(): string
    {
        $prefix = config('hostclient.ticket_prefix', 'TKT-');
        $number = str_pad(self::max('id') + 1, 6, '0', STR_PAD_LEFT);
        return $prefix . $number;
    }
}
