<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'application_id',
        'appointment_type',
        'first_name',
        'last_name',
        'middle_name',
        'extension',
        'phone_number',
        'control_number', // Add this!
        'requested_date',
        'status'
    ];
}
