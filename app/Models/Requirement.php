<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
  use HasFactory;

  protected $fillable = [
    'position_id',
    'requirement',
  ];

  public function position()
  {
    return $this->belongsTo(Position::class);
  }
}
