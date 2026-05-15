<?php

namespace App\Imports;

use App\Models\UniversitySlot;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UniversitySlotImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // যদি university_name খালি থাকে তবে স্কিপ করবে
        if (!isset($row['university_name']) || $row['university_name'] == null) {
            return null;
        }

        return UniversitySlot::updateOrCreate(
            ['university_name' => trim($row['university_name'])], // স্পেস ক্লিন করা
            [
                'category'  => $row['category'] ?? 'School/College', // ডিফল্ট ভ্যালু
                'max_slots' => (int) ($row['max_slots'] ?? 0), // ইন্টিজার নিশ্চিত করা
            ]
        );
    }
}
