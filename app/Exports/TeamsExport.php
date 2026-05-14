<?php


namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TeamsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * Collection method: University-r nam onujayi grouping korar jonno orderBy use kora hoyeche.
     */
    public function collection()
    {
        // 'university_name' onujayi sort korle ekoi university-r sob team eksathe thakbe
        return Registration::orderBy('university_name', 'asc')->get();
    }

    /**
     * Excel-er Header row set kora.
     */
    public function headings(): array
    {
        return [
            'university', // Grouped hobe
            'coach_name',
            'coach_email',
            'slots'        // User-er puron korar jonno faka thakbe
        ];
    }

    /**
     * Protiti row-r data mapping.
     * @var Registration $team
     */
    public function map($team): array
    {
        return [
            $team->university_name,
            $team->coach_name,
            $team->coach_email,
            '',                     // Eta faka thakbe (Image_cc443a.png-r moton)
        ];
    }
}
