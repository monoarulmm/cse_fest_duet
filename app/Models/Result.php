<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'participant_id',
        'event_name',
        'university_name',
        'team_name',
        'result_status'
    ];
}
