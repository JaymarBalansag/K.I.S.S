<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsRequest extends Model
{
    protected $fillable = [
        'phone_number',
        'message',
        'status',
        'sent_from',
        'source',
        'external_id',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
