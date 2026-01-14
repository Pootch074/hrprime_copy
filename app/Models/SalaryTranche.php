<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryTranche extends Model
{
    use HasFactory;

    // If your table is named 'salary_tranche' (singular)
    protected $table = 'salary_tranche';

    protected $fillable = ['tranche_name', 'effectivity_date'];


    public function grades()
    {
        return $this->hasMany(SalaryGrade::class, 'tranche_id');
    }
}
