<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction_id', // এই লাইনটি যুক্ত করা হলো
        'event_id',
        'team_id',
        'student_id',
        'amount',
        'currency',
        'status',
        'payment_method'
    ];
}
