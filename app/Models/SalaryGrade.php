<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryGrade extends Model
{
    use HasFactory;

    protected $table = 'salary_grades';
    protected $fillable = ['tranche_id', 'salary_grade']; // must match table columns

    public function tranche()
    {
        return $this->belongsTo(SalaryTranche::class, 'tranche_id');
    }

    public function steps()
    {
        return $this->hasMany(SalaryStep::class, 'grade_id');
    }
}
