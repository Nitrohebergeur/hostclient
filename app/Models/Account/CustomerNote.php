<?php

/*
 * This file is part of the Hostclient project.
 * It is the property of the Hostclient association.
 *
 * Personal and non-commercial use of this source code is permitted.
 * However, any use in a project that generates profit (directly or indirectly),
 * or any reuse for commercial purposes, requires prior authorization from Hostclient.
 *
 * To request permission or for more information, please contact our support:
 * https://Hostclient.com/client/support
 *
 * Learn more about Hostclient License at:
 * https://Hostclient.com/eula
 *
 * Year: 2025
 */

namespace App\Models\Account;

use App\Models\Admin\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property int|null $author_id
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Customer $customer
 * @property-read Admin|null $author
 */
class CustomerNote extends Model
{
    use HasFactory;

    protected $table = 'customer_notes';

    protected $fillable = [
        'customer_id',
        'author_id',
        'content',
    ];

    /**
     * Get the customer that owns this note.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the admin author who created this note.
     */
    public function author()
    {
        return $this->belongsTo(Admin::class, 'author_id');
    }

    /**
     * Get the author's name or "Unknown" if no author.
     */
    public function getAuthorNameAttribute(): string
    {
        return $this->author ? $this->author->username : __('global.unknown');
    }
}
