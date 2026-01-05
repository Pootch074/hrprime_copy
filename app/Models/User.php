<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'employee_id',
        'item_number_id',
        'first_name',
        'middle_name',
        'last_name',
        'extension_name',
        'username',
        'email',
        'password',
        'birthday',
        'place_of_birth',
        'gender',
        'civil_status',
        'blood_type',
        'height',
        'weight',
        'tel_no',
        'mobile_no',
        'perm_country',
        'citizenship',
        'dual_citizenship',
        'dual_citizenship_type',
        'deactivation_reason',
        'role', // keep for reference/display
        'employment_status_id',
        'division_id',
        'section_id',
        'profile_image',

        // Permanent address
        'perm_region', 'perm_province', 'perm_city', 'perm_barangay',
        'perm_street', 'perm_house_no', 'perm_zipcode',

        // Residential address
        'res_country', 'res_region', 'res_province', 'res_city', 'res_barangay',
        'res_street', 'res_house_no', 'res_zipcode',
    ];

    protected $hidden = ['password'];

    protected $dates = ['birthday'];

    // Accessor for age
    public function getAgeAttribute()
    {
        return $this->birthday ? Carbon::parse($this->birthday)->age : null;
    }

    // ===================== RELATIONSHIPS =====================

    public function division() { return $this->belongsTo(Division::class, 'division_id'); }
    public function section() { return $this->belongsTo(Section::class, 'section_id'); }
    public function position() { return $this->belongsTo(Position::class, 'position_id'); }
    public function employmentStatus() { return $this->belongsTo(EmploymentStatus::class, 'employment_status_id'); }
    public function itemNumber() { return $this->belongsTo(ItemNumber::class); }

    // Permanent address
    public function permRegion() { return $this->belongsTo(Region::class, 'perm_region', 'psgc'); }
    public function permProvince() { return $this->belongsTo(Province::class, 'perm_province', 'psgc'); }
    public function permCity() { return $this->belongsTo(Municipality::class, 'perm_city', 'psgc'); }
    public function permBarangay() { return $this->belongsTo(Barangay::class, 'perm_barangay', 'psgc'); }

    // Residential address
    public function resRegion() { return $this->belongsTo(Region::class, 'res_region', 'psgc'); }
    public function resProvince() { return $this->belongsTo(Province::class, 'res_province', 'psgc'); }
    public function resCity() { return $this->belongsTo(Municipality::class, 'res_city', 'psgc'); }
    public function resBarangay() { return $this->belongsTo(Barangay::class, 'res_barangay', 'psgc'); }

    // PDS Sections
    public function governmentIds() { return $this->hasMany(GovernmentId::class, 'user_id'); }
    public function familyBackgrounds() { return $this->hasMany(FamilyBackground::class, 'user_id'); }
    public function children() { return $this->hasMany(Child::class, 'family_background_id'); }
    public function educations() { return $this->hasMany(Education::class, 'user_id'); }
    public function csEligibilities() { return $this->hasMany(CsEligibility::class, 'user_id'); }
    public function workExperiences() { return $this->hasMany(WorkExperience::class, 'user_id'); }
    public function voluntaryWorks() { return $this->hasMany(VoluntaryWork::class, 'user_id'); }
    public function learningAndDevelopments() { return $this->hasMany(LearningandDevelopment::class, 'user_id'); }
    public function skills() { return $this->hasMany(Skill::class, 'user_id'); }
    public function nonAcademics() { return $this->hasMany(NonAcademic::class, 'user_id'); }
    public function organizations() { return $this->hasMany(Organization::class, 'user_id'); }
    public function references() { return $this->hasMany(Reference::class, 'user_id'); }
    public function otherInformations() { return $this->hasMany(OtherInformation::class, 'user_id'); }

    // Optional helper to get primary role
    public function primaryRole()
    {
        return $this->role ?? $this->getRoleNames()->first();
    }
}
