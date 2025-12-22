<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmploymentStatus;

class EmploymentStatusSeeder extends Seeder
{
  public function run(): void
  {
    $statuses = [
      ['name' => 'Permanent', 'abbreviation' => 'PERM'],
      ['name' => 'Contractual', 'abbreviation' => 'CONT'],
      ['name' => 'Casual', 'abbreviation' => 'CAS'],
      ['name' => 'Coterminous', 'abbreviation' => 'COT'],
      ['name' => 'Contract of Service', 'abbreviation' => 'COS'],
    ];

    foreach ($statuses as $status) {
      EmploymentStatus::updateOrCreate(
        ['name' => $status['name']],
        ['abbreviation' => $status['abbreviation']]
      );
    }
  }
}
