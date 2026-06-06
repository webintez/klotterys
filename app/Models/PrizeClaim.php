<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrizeClaim extends Model
{
    protected $fillable = [
        'ticket_number',
        'mobile',
        'registration_fee',
        'screenshot',
        'status',
    ];
}
