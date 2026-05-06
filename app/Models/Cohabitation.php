<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cohabitation extends Model
{
    /** @use HasFactory<\Database\Factories\CohabitationFactory> */
    use HasFactory;


    protected $fillable = [
        'control_number',
        'residence',
        'cohabitation_start_date',
    ];

    public function partners()
    {
        return $this->hasMany(Cohabitation_Partner::class, 'cohabitation_id');
    }
}
