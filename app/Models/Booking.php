<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'fullname',
        'mobile',
        'state',
        'pincode',
        'tickets',
        'total_price',
        'status',
        'tracking_number',
    ];
}
