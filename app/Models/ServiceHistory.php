<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceHistory extends Model
{
    protected $table = 'service_history';

    protected $fillable = [
        'service_id',
        'action',
        'description',
        'metadata',
        'user_id',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
