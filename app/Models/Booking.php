<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'name',
        'contact_number',
        'booking_date',
    ];

    protected $casts = [
        'booking_date' => 'datetime',
    ];
}
