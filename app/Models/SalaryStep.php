<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStep extends Model
{
    use HasFactory;

    protected $table = 'salary_step';
    protected $fillable = ['grade_id', 'step', 'salary_amount']; // matches table

    public function grade()
    {
        return $this->belongsTo(SalaryGrade::class, 'grade_id');
    }
}
