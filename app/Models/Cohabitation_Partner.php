<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cohabitation_Partner extends Model
{
    protected $fillable = [
        'cohabitation_id',
        'partner_type',
        'first_name',
        'middle_name',
        'suffix',
        'last_name',
        'id_type',
        'id_number',
        'issued_at',
        'issued_on',
    ];

    public function cohabitation()
    {
        return $this->belongsTo(Cohabitation::class);
    }
}
