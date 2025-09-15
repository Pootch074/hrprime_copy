<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use HasFactory, Notifiable;

  protected $fillable = [
    'employee_id',
    'first_name',
    'middle_name',
    'last_name',
    'extension_name',
    'employment_status_id',
    'division_id',
    'section_id',
    'username',
    'email',
    'password',
    'profile_image'
  ];

  protected $hidden = [
    'password',
  ];

  public function division()
  {
    return $this->belongsTo(Division::class);
  }

  public function section()
  {
    return $this->belongsTo(Section::class);
  }

  public function employmentStatus()
  {
    return $this->belongsTo(EmploymentStatus::class, 'employment_status_id');
  }

  public function qualification()
  {
    return $this->belongsTo(Qualification::class);
  }

  public function courses()
  {
    return $this->belongsToMany(\App\Models\Course::class, 'course_enrollments')
      ->withPivot('status')
      ->withTimestamps();
  }
}
