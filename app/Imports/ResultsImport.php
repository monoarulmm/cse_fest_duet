<?php

namespace App\Imports;

namespace App\Imports;

use App\Models\Result;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ResultsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // নিশ্চিত করুন যে আপনার এক্সেল ফাইলের কলামের নাম এগুলোর সাথে হুবহু মিলে
        return new Result([
            'participant_id' => $row['participant_id'],
            'event_name'     => $row['event_name'],
            'university_name'     => $row['university_name'],
            'team_name'     => $row['team_name'],
            'seat_plan'     => $row['seat_plan'],
            'result_status'  => $row['result_status'],
        ]);
    }
}
