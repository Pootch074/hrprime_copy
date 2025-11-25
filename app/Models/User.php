<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

<<<<<<< HEAD
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
        'gender',
        'role',
        
        

        // ✅ Permanent address fields
        'perm_region',
        'perm_province',
        'perm_city',
        'perm_barangay',

        // ✅ Residential address fields
        'res_region',
        'res_province',
        'res_city',
        'res_barangay',
    ];

    protected $hidden = [
        'password',
    ];
=======
  protected $fillable = [
    'employee_id',
    'item_number_id',
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
    'gender',
    'role', // ✅ add this line
  ];

  protected $hidden = [
    'password',
  ];
  public function division()
  {
    return $this->belongsTo(Division::class, 'division_id');
  }

  public function section()
  {
    return $this->belongsTo(Section::class, 'section_id');
  }

  public function position()
  {
    return $this->belongsTo(Position::class, 'position_id');
  }

  public function getSections(Request $request)
  {
    $divisionId = $request->division_id;
>>>>>>> bf0251197533e5c0a2f7d041bd46ee2e8142b8c1

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function getSections(Request $request)
    {
        $divisionId = $request->division_id;

        if (!$divisionId) {
            return response()->json([]);
        }

        $sections = Section::where('division_id', $divisionId)
            ->get(['id', 'name']);

        return response()->json($sections);
    }

    public function employmentStatus()
    {
        return $this->belongsTo(EmploymentStatus::class, 'employment_status_id');
    }

    public function model(array $row)
    {
        Log::info('IMPORTING ROW: ', $row);
        if (strtolower($row[0]) === 'username') return null;
    }

    // ✅ Permanent address relationships
    public function permRegion()
    {
        return $this->belongsTo(Region::class, 'perm_region', 'psgc');
    }

    public function permProvince()
    {
        return $this->belongsTo(Province::class, 'perm_province', 'psgc');
    }

    public function permCity()
    {
        return $this->belongsTo(Municipality::class, 'perm_city', 'psgc');
    }

    public function permBarangay()
    {
        return $this->belongsTo(Barangay::class, 'perm_barangay', 'psgc');
    }

    // ✅ Residential address relationships
    public function resRegion()
    {
        return $this->belongsTo(Region::class, 'res_region', 'psgc');
    }

    public function resProvince()
    {
        return $this->belongsTo(Province::class, 'res_province', 'psgc');
    }

    public function resCity()
    {
        return $this->belongsTo(Municipality::class, 'res_city', 'psgc');
    }

    public function resBarangay()
    {
        return $this->belongsTo(Barangay::class, 'res_barangay', 'psgc');
    }
    public function governmentIds()
    {
        return $this->hasMany(GovernmentId::class, 'user_id', 'id');
    }
    public function familyBackgrounds()
    {
        return $this->hasMany(FamilyBackground::class, 'employee_id', 'id');
    }
    public function children()
    {
        return $this->hasMany(Child::class, 'family_background_id');
    }
    public function educations()
    {
        return $this->hasMany(Education::class, 'user_id', 'id');
    }
    public function csEligibilities()
    {
        return $this->hasMany(CsEligibility::class,  'user_id', 'id');
    }

    public function workExperiences()
    {
        return $this->hasMany(WorkExperience::class,  'user_id', 'id');
    }
    public function voluntaryWorks()
    {
        return $this->hasMany(VoluntaryWork::class,  'user_id', 'id');
    }
    public function learningAndDevelopments()
    {
        return $this->hasMany(LearningAndDevelopment::class,  'user_id', 'id');
    }
    public function skills()
    {
        return $this->hasMany(Skill::class,  'user_id', 'id');
    }
    public function nonAcademics()
    {
        return $this->hasMany(NonAcademic::class,  'user_id', 'id');
    }
    public function organizations()
    {
        return $this->hasMany(Organization::class,  'user_id', 'id');
    }
    public function references()
    {
        return $this->hasMany(Reference::class,  'user_id', 'id');
    }
    public function otherInformations()
    {
        return $this->hasMany(OtherInformation::class,  'user_id', 'id');
    }



<<<<<<< HEAD
=======
    // ... rest of logic
  }
  public function getFullNameAttribute()
  {
    $names = [
      $this->first_name,
      $this->middle_name,
      $this->last_name,
      $this->extension_name
    ];

    // Filter out null or empty values and join with spaces
    return implode(' ', array_filter($names));
  }
  public function itemNumber()
  {
    return $this->belongsTo(ItemNumber::class);
  }
>>>>>>> bf0251197533e5c0a2f7d041bd46ee2e8142b8c1
}
