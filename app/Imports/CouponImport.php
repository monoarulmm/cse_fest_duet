<?php

namespace App\Imports;

use App\Models\Coupon;
use App\Mail\CoachSlotMail; // ১. মেইল ক্লাসটি অবশ্যই ইম্পোর্ট করতে হবে
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail; // ২. সঠিক ফাসাদ (Facade) ব্যবহার করুন
use Illuminate\Support\Facades\DB;   // ৩. ট্রানজ্যাকশনের জন্য

class CouponImport implements ToCollection, WithHeadingRow
{
    protected $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // ৪. ডাটাবেস ট্রানজ্যাকশন ব্যবহার করা ভালো যাতে মেইল ফেইল করলে বা এরর হলে ডাটা অসম্পূর্ণ না থাকে
            DB::transaction(function () use ($row) {
                $generatedCodes = [];
                $slots = (int) $row['slots']; // ডাটা টাইপ নিশ্চিত করুন

                for ($i = 0; $i < $slots; $i++) {
                    $code = strtoupper(Str::random(8));

                    // কুপন তৈরি
                    Coupon::create([
                        'university'   => $row['university'],
                        'coach_name'   => $row['coach_name'],
                        'coach_email'  => $row['coach_email'],
                        'code'         => $code,
                        'event_id'     => $this->eventId,
                    ]);

                    $generatedCodes[] = $code;
                }

                // ৫. মেইল পাঠানোর সময় ডাটা পাস করা
                if (!empty($row['coach_email'])) {
                    Mail::to($row['coach_email'])->send(new CoachSlotMail($generatedCodes, $row['coach_name']));
                }
            });
        }
    }
}
