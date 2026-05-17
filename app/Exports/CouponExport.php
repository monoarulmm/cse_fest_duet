<?php

namespace App\Exports;

use App\Models\Coupon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CouponExport implements FromCollection, WithHeadings, WithMapping
{
    protected $eventId;

    // কোন নির্দিষ্ট ইভেন্টের কুপন ডাউনলোড করবেন তার আইডি পাস করার জন্য
    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    /**
     * নির্দিষ্ট ইভেন্টের কুপন কালেকশন রিটার্ন করবে
     */
    public function collection()
    {
        return Coupon::where('event_id', $this->eventId)->get();
    }

    /**
     * এক্সেল ফাইলের হেডার রো (Header Row)
     */
    public function headings(): array
    {
        return [
            'ID',
            'University',
            'Coach Name',
            'Coach Email',
            'Coach Phone',
            'Coupon Code',
            'Status',
            'Created At'
        ];
    }

    /**
     * প্রতিটা রোর ডাটা ম্যাপ করা (কোডসহ)
     */
    public function map($coupon): array
    {
        return [
            $coupon->id,
            $coupon->university,
            $coupon->coach_name,
            $coupon->coach_email,
            $coupon->coach_phone,
            $coupon->code, // জেনারেট হওয়া আসল কোড
            $coupon->status ?? 'active',
            $coupon->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
