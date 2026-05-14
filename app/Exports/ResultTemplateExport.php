<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ResultTemplateExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    /**
     * ডাটাবেস কুয়েরি: ইভেন্ট আইডি এবং পেমেন্ট স্ট্যাটাস ফিল্টার করা হচ্ছে।
     * with('event') ব্যবহার করা হয়েছে যাতে ম্যাপ করার সময় ডাটা দ্রুত পাওয়া যায়।
     */
    public function query()
    {
        return Registration::query()
            ->with('event') // ইভেন্ট মডেলের ডাটা ইগার লোড (Eager Loading)
            ->where('event_id', $this->eventId)
            ->where('payment_status', 'paid'); // আপনার ডাটাবেস ভ্যালু অনুযায়ী 'paid' ঠিক আছে
    }

    /**
     * এক্সেল ফাইলের হেডার: 
     * আপনার 'ResultsImport' ক্লাসের সাথে কলামের সিরিয়াল হুবহু মিল থাকতে হবে।
     */
    public function headings(): array
    {
        return [
            'participant_id',  // ইমপোর্টে $row['participant_id'] হিসেবে রিড হবে
            'event_name',      // ইমপোর্টে $row['event_name'] হিসেবে রিড হবে
            'university_name',    // অতিরিক্ত তথ্যের জন্য
            'team_name',    // অতিরিক্ত তথ্যের জন্য
            'seat_plan',    // অতিরিক্ত তথ্যের জন্য
            'result_status'    // এই কলামটি খালি থাকবে
        ];
    }

    /**
     * ডাটা ম্যাপিং:
     */
    public function map($reg): array
    {
        return [
            $reg->participant_id,
            $reg->event->name ?? 'N/A',
            $reg->university_name ?? 'N/A',
            $reg->team_name ?? $reg->m1_name, // টিম না থাকলে লিডারের নাম দেখাবে
            '', // seat_plan কলামটি খালি (ম্যানুয়ালি বসানোর জন্য)
            '', // result_status কলামটি খালি (ম্যানুয়ালি বসানোর জন্য)
        ];
    }
}
