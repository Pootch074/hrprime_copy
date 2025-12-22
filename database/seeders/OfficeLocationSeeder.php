<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OfficeLocation;

class OfficeLocationSeeder extends Seeder
{
  public function run(): void
  {
    $offices = [
      ['name' => 'RPMO', 'abbreviation' => 'RPMO'],
      ['name' => 'Davao City', 'abbreviation' => 'DC'],
      ['name' => 'Davao Del Sur', 'abbreviation' => 'DDS'],
      ['name' => 'Davao Del Norte', 'abbreviation' => 'DDN'],
      ['name' => 'Davao Oriental', 'abbreviation' => 'DO'],
      ['name' => 'Davao De Oro', 'abbreviation' => 'DDO'],
      ['name' => 'Davao Occidental', 'abbreviation' => 'DOC'],
    ];

    foreach ($offices as $office) {
      OfficeLocation::updateOrCreate(
        ['name' => $office['name']],
        ['abbreviation' => $office['abbreviation']]
      );
    }
  }
}
