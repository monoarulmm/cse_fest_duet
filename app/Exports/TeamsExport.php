<?php


namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TeamsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        // ১. প্রথমে 'iupc' স্ল্যাগ বিশিষ্ট ইভেন্টটি খুঁজে বের করা
        // এখানে ধরে নেওয়া হচ্ছে আপনার Event মডেল আছে এবং সেখানে 'slug' কলাম আছে
        return Registration::whereHas('event', function ($query) {
            $query->where('slug', 'iupc'); // ইভেন্ট টেবিলের স্ল্যাগ চেক করা হচ্ছে
        })
            ->orderBy('university_name', 'asc')
            ->orderBy('coach_name', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'university', // Grouped hobe
            'coach_name',
            'coach_email',
            'slots'        // User-er puron korar jonno faka thakbe
        ];
    }

    public function map($team): array
    {
        return [
            $team->university_name,
            $team->coach_name,
            $team->coach_email,
            '', // Slots কলাম ফাঁকা
            '', // Codes কলাম ফাঁকা
        ];
    }
}
