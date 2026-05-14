<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'event_id',
    //     'university_name',
    //     'team_name',

    //     // Member 1 (Lead/Solo)
    //     'm1_name',
    //     'm1_email',
    //     'm1_phone',
    //     'm1_tshirt',
    //     'student_id',

    //     // Member 2
    //     'm2_name',
    //     'm2_email',
    //     'm2_phone',
    //     'm2_tshirt',

    //     // Member 3
    //     'm3_name',
    //     'm3_email',
    //     'm3_phone',
    //     'm3_tshirt',

    //     // IUPC Specific
    //     'coach_name',
    //     'coach_designation',
    //     'coach_email',
    //     'coach_phone',
    //     'coach_tshirt',
    //     'm1_cf_handle',
    //     'm2_cf_handle',
    //     'm3_cf_handle',
    //     'coupon_code',

    //     // Project Showcase Specific
    //     'project_title',
    //     'domain',
    //     'abstract_file',

    //     // System & Payment
    //     'participant_id',
    //     'payment_status',
    //     'status'
    // ];

    /**
     * ইভেন্টের সাথে রিলেশন
     */
    // public function event()
    // {
    //     return $this->belongsTo(Event::class);
    // }

    protected $fillable = [
        'event_id',
        'university_name',
        'team_name',
        'team_id',
        'm1_name',
        'm1_email',
        'm1_phone',
        'm1_tshirt',
        'student_id',
        'm2_name',
        'm2_email',
        'm2_phone',
        'm2_tshirt',
        'm3_name',
        'm3_email',
        'm3_phone',
        'm3_tshirt',
        'coach_name',
        'coach_designation',
        'coach_email',
        'coach_phone',
        'coach_desgination',
        'coach_tshirt',
        'm1_cf_handle',
        'm2_cf_handle',
        'm3_cf_handle',
        'coupon_code',
        'project_title',
        'domain',
        'abstract_file',


        'participant_id',
        'payment_status',
        'status',
        'prev_ex'
    ];
    public function event()
    {
        // নিশ্চিত করুন যে আপনার টেবিলে 'event_id' কলামটি আছে
        return $this->belongsTo(Event::class, 'event_id');
    }
    /**
     * ট্রানজেকশন টেবিলের সাথে রিলেশন (আগের আলোচনা অনুযায়ী)
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'registration_id');
    }

    // --- Useful Scopes (সহজে ডাটা ফিল্টার করার জন্য) ---

    public function scopeIupc($query)
    {
        return $query->whereHas('event', function ($q) {
            $q->where('slug', 'iupc');
        });
    }

    public function scopeShowcase($query)
    {
        return $query->whereHas('event', function ($q) {
            $q->where('slug', 'project-showcase');
        });
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }
}
