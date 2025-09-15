<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionLevel extends Model
{
    use HasFactory;

    protected $fillable = ['level_name', 'abbreviation', 'level_rank'];

    // Relationship to positions
    public function positions()
    {
        return $this->hasMany(Position::class);
    }
}
    