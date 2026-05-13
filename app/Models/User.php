<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // অথেন্টিকেশনের জন্য এটি জরুরি

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'institution',
        'password',
        'verification_code',
        'is_verified'
    ];

    // পাসওয়ার্ড সেভ করার সময় অটোমেটিক এনক্রিপ্ট করার জন্য (Optional but Good)
    protected $hidden = ['password'];
}
