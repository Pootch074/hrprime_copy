<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacantPosition extends Model
{
    use HasFactory;

    protected $table = 'is_vacant';

    protected $fillable = [
        'position_id',        // <-- make sure this is in your table
        'qualification_id',
    ];

    public function qualification()
    {
        return $this->belongsTo(Qualification::class);
    }

    public function qualifiedStaff()
    {
        return $this->hasMany(User::class, 'qualification_id', 'qualification_id');
    }

    // ✅ This is the one you asked about:
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }
}
