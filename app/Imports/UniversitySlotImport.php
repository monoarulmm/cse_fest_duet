<?php

namespace App\Imports;

use App\Models\UniversitySlot;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UniversitySlotImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // একই নামের ইউনিভার্সিটি থাকলে তা আপডেট করবে, না থাকলে নতুন তৈরি করবে
        return UniversitySlot::updateOrCreate(
            ['university_name' => $row['university_name']],
            [
                'category'   => $row['category'],
                'max_slots'  => $row['max_slots'],
            ]
        );
    }
}
