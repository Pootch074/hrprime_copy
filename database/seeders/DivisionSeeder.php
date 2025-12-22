<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionSeeder extends Seeder
{
  public function run(): void
  {
    $divisions = [
      ['name' => 'Office of the Regional Director', 'abbreviation' => 'ORD'],
      ['name' => 'Office of the Assistant Regional Director for Operation', 'abbreviation' => 'ARDO'],
      ['name' => 'Office of the Assistant Regional Director for Administration', 'abbreviation' => 'ARDA'],
      ['name' => 'Promotive Services Division', 'abbreviation' => 'PSD'],
      ['name' => 'Disaster Response Management Division', 'abbreviation' => 'DRMD'],
      ['name' => 'Protective Services Division', 'abbreviation' => 'PSVD'], // ✅ fixed
      ['name' => 'Provincial Social Welfare and Development Office', 'abbreviation' => 'PSWDO'],
      ['name' => 'Pantawid Pamilyang Pilipino Program Management', 'abbreviation' => '4Ps'],
      ['name' => 'Policy and Plans Division', 'abbreviation' => 'PPD'],
      ['name' => 'Administrative Division', 'abbreviation' => 'AD'],
      ['name' => 'Human Resource Management and Development Division', 'abbreviation' => 'HRMDD'],
      ['name' => 'Financial Management Division', 'abbreviation' => 'FMD'],
      ['name' => 'Resource Management', 'abbreviation' => 'RM'],
      ['name' => 'Regional Juvenile Justice Welfare Council', 'abbreviation' => 'RJJWC'],
      ['name' => 'Innovations Division', 'abbreviation' => 'ID'],
    ];

    foreach ($divisions as $division) {
      Division::updateOrCreate(
        ['abbreviation' => $division['abbreviation']],
        ['name' => $division['name']]
      );
    }
  }
}
