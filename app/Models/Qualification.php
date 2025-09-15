<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
  use HasFactory;

  /**
   * Mass assignable fields
   */
  protected $fillable = [
    'title',
    'description',
  ];

  /**
   * A qualification can be linked to many positions
   * via the pivot table: position_qualification
   */
  public function positions()
  {
    return $this->belongsToMany(Position::class, 'position_qualification')
      ->withTimestamps();
  }

  /**
   * (Optional) A qualification may be linked to vacant positions
   */
  public function vacantPositions()
  {
    return $this->hasMany(VacantPosition::class);
  }

  /**
   * (Optional) A qualification may be linked to users
   */
  public function users()
  {
    return $this->hasMany(User::class, 'qualification_id');
  }
}
