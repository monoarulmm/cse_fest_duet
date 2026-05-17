<?php

namespace App\Imports;

use App\Models\Coupon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CouponImport implements ToCollection, WithHeadingRow
{
    protected $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function collection(Collection $rows)
    {
        // ১. ইউনিভার্সিটি অনুযায়ী ডাটা গ্রুপ করছি
        $groupedByUniversity = $rows->groupBy(function ($item) {
            return trim($item['university'] ?? '');
        });

        DB::transaction(function () use ($groupedByUniversity) {
            foreach ($groupedByUniversity as $universityName => $universityRows) {

                if (empty($universityName)) {
                    continue;
                }

                // ২. ওই ইউনিভার্সিটির ভেতরের ইউনিক কোচদের ইমেইল ও নাম বের করছি
                $uniqueEmails = [];
                $uniqueNames = [];
                $uniquePhones = [];
                $totalSlots = 0;

                foreach ($universityRows as $row) {
                    $totalSlots += (int) ($row['slots'] ?? 0);

                    $email = trim(strtolower($row['coach_email'] ?? $row['email'] ?? ''));
                    $name = trim($row['coach_name'] ?? $row['name'] ?? '');
                    $phone = trim($row['coach_phone'] ?? $row['phone'] ?? '');

                    if (!empty($email) && !in_array($email, $uniqueEmails)) {
                        $uniqueEmails[] = $email;
                    }
                    if (!empty($name) && !in_array($name, $uniqueNames)) {
                        $uniqueNames[] = $name;
                    }
                    if (!empty($phone) && !in_array($phone, $uniquePhones)) {
                        $uniquePhones[] = $phone;
                    }
                }

                if ($totalSlots <= 0) {
                    continue;
                }

                // ৩. সব ইউনিক ইমেইল, নাম ও ফোন নম্বর কমা (,) দিয়ে যুক্ত করে একটি স্ট্রিং বানাচ্ছি
                $coachEmailsString = implode(', ', $uniqueEmails);
                $coachNamesString = implode(', ', $uniqueNames);
                $coachPhonesString = implode(', ', $uniquePhones);

                // ৪. ডুপ্লিকেট চেক: এই ইভেন্টে এই ইউনিভার্সিটির কুপন অলরেডি তৈরি আছে কিনা
                $exists = Coupon::where('event_id', $this->eventId)
                    ->where('university', $universityName)
                    ->exists();

                if ($exists) {
                    continue; // অলরেডি ইমপোর্ট করা থাকলে স্কিপ করবে
                }

                // ৫. টোটাল স্লট (যেমন ৫টি) অনুযায়ী লুপ ঘুরিয়ে কুপন তৈরি
                for ($i = 0; $i < $totalSlots; $i++) {

                    do {
                        $code = strtoupper(Str::random(8));
                    } while (Coupon::where('code', $code)->exists());

                    Coupon::create([
                        'university'   => $universityName,
                        'coach_name'   => $coachNamesString,  // সব কোচের নাম একসাথে থাকবে
                        'coach_email'  => $coachEmailsString, // সব কোচের ইমেইল একসাথে কমা দিয়ে থাকবে
                        'code'         => $code,
                        'event_id'     => $this->eventId,
                    ]);
                }
            }
        });
    }
}
