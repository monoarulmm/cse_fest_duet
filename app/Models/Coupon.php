<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['university', 'coach_name', 'coach_email', 'coach_phone', 'code', 'event_id', 'status'];

    // code কলামটিকে অ্যারে হিসেবে কাস্ট করুন

}
