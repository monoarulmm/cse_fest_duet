<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RegistrationExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function query()
    {
        return Registration::query()->where('event_id', $this->eventId);
    }

    public function headings(): array
    {
        return [
            'University',
            'Team Name',
            'Coach Name',
            'Coach Email',
            'Coach Phone',
            'Member-1 Name',
            'Member-1 Email',
            'Member-1 CF Handle',
            'Member-2 Name',
            'Member-2 Email',
            'Member-2 CF Handle',
            'Member-3 Name',
            'Member-3 Email',
            'Member-3 CF Handle',
            'Payment Status'
        ];
    }

    public function map($reg): array
    {
        return [
            $reg->university_name,
            $reg->team_name ?? 'Individual',
            $reg->coach_name,
            $reg->coach_email,
            $reg->coach_phone,
            $reg->m1_name,
            $reg->m1_email,
            $reg->m1_cf_handle,
            $reg->m2_name ?? 'N/A',
            $reg->m2_email ?? 'N/A',
            $reg->m2_cf_handle ?? 'N/A',
            $reg->m3_name ?? 'N/A',
            $reg->m3_email ?? 'N/A',
            $reg->m3_cf_handle ?? 'N/A',
            strtoupper($reg->payment_status),
        ];
    }
}
