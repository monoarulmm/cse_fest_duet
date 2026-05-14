<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'university',
        'coach_name',
        'coach_email',
        'code',
        'is_used',
        'event_id' // এটি নিশ্চিত করুন
    ];
}
