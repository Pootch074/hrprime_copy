<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_level_id',
        'position_name',
        'abbreviation',
        'item_no',
        'salary_grade_id',
        'employment_status_id',
        'status',
        'division_id',
        'section_id',
        'program',
        'official_station',
        'parenthetical_title',
        'level',
        'salary_step',
        'monthly_rate',
        'designation',
        'special_order',
        'obsu',
        'fund_source',
        'type_of_request',
    ];

    public function division() {
        return $this->belongsTo(\App\Models\Division::class);
    }

    public function section() {
        return $this->belongsTo(\App\Models\Section::class);
    }

    public function employmentStatus() {
        return $this->belongsTo(\App\Models\EmploymentStatus::class);
    }

    public function salaryGrade() {
        return $this->belongsTo(\App\Models\SalaryGrade::class);
    }

    public function levelRelation() {
        return $this->belongsTo(\App\Models\PositionLevel::class, 'position_level_id');
    }
}
