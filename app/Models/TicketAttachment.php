<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    protected $fillable = [
        'ticket_reply_id',
        'filename',
        'path',
        'mime_type',
        'size',
    ];

    public function reply()
    {
        return $this->belongsTo(TicketReply::class, 'ticket_reply_id');
    }
}
