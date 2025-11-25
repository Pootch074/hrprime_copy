<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicants extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_number',
        'applicant_no',
        'first_name',
        'middle_name',
        'last_name',
        'extension_name',
        'sex',
        'date_of_birth',
        'date_applied',
        'status',
        'remarks',
        'date_hired',
        'archived'
    ];
}

