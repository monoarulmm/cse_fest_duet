<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UniversitySlot extends Model
{
    // app/Models/UniversitySlot.php

    protected $fillable = [
        'university_name',
        'category',
        'max_slots'
    ];
}
