<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'reg_fee',
        'min_members',
        'max_members',
        'description', // নতুন যুক্ত
        'rules',       // নতুন যুক্ত
        'result',      // নতুন যুক্ত
        'seatplan',    // নতুন যুক্ত
        'images',      // নতুন যুক্ত
        'start_date',
        'end_date',
        'is_active',
        'needs_coach'
    ];

    /**
     * Casting logic
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'needs_coach' => 'boolean',
        'images' => 'array', // ডাটাবেসের JSON স্ট্রিংকে অটোমেটিক অ্যারেতে রূপান্তর করবে
    ];

    /**
     * চেক করবে রেজিস্ট্রেশনের সময় এখনো আছে কি না।
     */
    public function isOpen()
    {
        // নিশ্চিত করুন end_date এবং is_active নাল নয়
        return $this->is_active && $this->end_date && $this->end_date->isFuture();
    }

    /**
     * রেজিস্ট্রেশন শেষ হতে আর কত সময় বাকি তা দেখাবে।
     */
    public function timeLeft()
    {
        if (!$this->end_date || $this->end_date->isPast()) {
            return "Registration Closed";
        }
        return $this->end_date->diffForHumans();
    }

    /**
     * স্লাগ দিয়ে ইভেন্ট খুঁজে বের করার জন্য (Helper)
     */
    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->firstOrFail();
    }
}
