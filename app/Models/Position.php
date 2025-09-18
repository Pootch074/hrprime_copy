<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
  protected $fillable = ['position_name', 'abbreviation'];

  public function requirements()
  {
    return $this->hasMany(Requirement::class);
  }

  public function salaryGrade()
  {
    return $this->belongsTo(SalaryGrade::class);
  }

  public function employmentStatus()
  {
    return $this->belongsTo(EmploymentStatus::class);
  }
  public function itemNumbers()
  {
    return $this->hasMany(ItemNumber::class);
  }
}
