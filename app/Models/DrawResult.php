<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrawResult extends Model
{
    protected $fillable = [
        'draw_date',
        'lottery_name',
        'draw_number',
        'winning_number',
        'prize_category',
        'winning_amount',
    ];
}
