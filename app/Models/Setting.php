<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'favicon',
        'site_name',
        'main_banner1',
        'main_banner2',
        'main_banner3',
        'sponsor_banner',
        'phone_primary',
        'phone_secondary',
        'email',
        'address',
        'fb_link',
        'youtube_link',
        'whatsapp_link'
    ];

    protected $casts = [
        'sponsor_banner' => 'array',
    ];

    /**
     * যদি আপনি চান যে পুরো প্রজেক্টে একটি মাত্র সেটিংস রো থাকুক, 
     * তবে নিচে একটি হেল্পার মেথড যোগ করতে পারেন (ঐচ্ছিক)।
     */
    public static function getSettings()
    {
        return self::first();
    }
}
