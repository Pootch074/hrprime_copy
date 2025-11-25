<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyBackground extends Model
{
    use HasFactory;

<<<<<<< HEAD
    protected $table = 'family_backgrounds'; // Table name

    protected $fillable = [
        // Spouse
        'spouse_surname', 'spouse_first_name', 'spouse_middle_name', 'spouse_extension_name', 'mother_maiden_name',
        'spouse_occupation', 'spouse_employer', 'spouse_employer_address', 'spouse_employer_telephone',

        // Father
        'father_surname', 'father_first_name', 'father_middle_name', 'father_extension_name',

        // Mother
        'mother_surname', 'mother_first_name', 'mother_middle_name', 'mother_extension_name',

        // Reference to employee
        'employee_id',
    ];
    
    public function children()
{
    return $this->hasMany(Child::class);
}
=======
    protected $table = 'family_backgrounds';

    protected $fillable = [
        // spouse
        's_lname', 's_fname', 's_mname', 's_ext',
        's_occ', 's_emp', 's_addr',

        // father
        'f_lname', 'f_fname', 'f_mname', 'f_ext',

        // mother
        'm_lname', 'm_fname', 'm_mname', 'm_ext',

        // children
        'children',
    ];

    protected $casts = [
        'children' => 'array', // automatically JSON encode/decode
    ];
>>>>>>> dc4e45e32fbe22834c7fa85287e2e37c57d782bc
}
