<?php

namespace App\Exports;

use App\Models\Coupon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CouponExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $eventId;

    // কোন নির্দিষ্ট ইভেন্টের কুপন ডাউনলোড করবেন তার আইডি পাস করার জন্য
    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    /**
     * ইউনিভার্সিটি অনুযায়ী গ্রুপ করে কুপন কোডগুলো মার্জ করে রিটার্ন করবে
     */
    public function collection()
    {
        // ১. ওই নির্দিষ্ট ইভেন্টের সব কুপন তুলে আনা হলো
        $coupons = Coupon::where('event_id', $this->eventId)->get();

        // ২. ডাটাবেজের রেকর্ডগুলোকে ইউনিভার্সিটি অনুযায়ী গ্রুপ করা হচ্ছে
        $groupedCoupons = $coupons->groupBy('university');

        $exportData = collect();
        $sl = 1;

        foreach ($groupedCoupons as $universityName => $universityRows) {

            // ৩. এই ইউনিভার্সিটির আন্ডারে থাকা সব কুপন কোড কমা দিয়ে এক সুতায় বাঁধা হচ্ছে
            $couponCodesString = $universityRows->pluck('code')->implode(', ');

            // ৪. ফার্স্ট রো থেকে কমন ইনফোগুলো পিক করা হচ্ছে (যেহেতু ইমপোর্টের সময় এগুলো মার্জ ছিল)
            $firstRow = $universityRows->first();

            // ৫. স্লট বা টোটাল কুপন সংখ্যা কাউন্ট করা হচ্ছে
            $totalSlots = $universityRows->count();

            // কাস্টম এক্সেল রোর ডাটা স্ট্রাকচার
            $exportData->push([
                'sl'          => $sl++,
                'university'  => $universityName,
                'coach_name'  => $firstRow->coach_name,
                'coach_email' => $firstRow->coach_email,
                'total_slots' => $totalSlots,
                'coupon_codes' => $couponCodesString, // সব কোড একসাথে দেখাবে
                'created_at'  => $firstRow->created_at->format('Y-m-d'),
            ]);
        }

        return $exportData;
    }

    /**
     * এক্সেল ফাইলের হেডার রো (Header Row)
     */
    public function headings(): array
    {
        return [
            'SL',
            'University Name',
            'Coach Name',
            'Coach Email',
            'Total Slots / Coupons',
            'Generated Coupon Codes', // এই কলামে মার্জড কোডগুলো থাকবে
            'Created At'
        ];
    }
}
