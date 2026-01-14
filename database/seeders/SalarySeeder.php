<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SalarySeeder extends Seeder
{
    public function run(): void
    {
        // Clear previous data safely
        DB::table('salary_step')->delete();
        DB::table('salary_grade')->delete();
        DB::table('salary_tranche')->delete();

        // --- 1. Create Salary Tranches ---
        $tranches = [
            ['tranche_name' => 'Tranche 2026', 'effectivity_date' => '2026-01-01', 'is_active' => true],
            ['tranche_name' => 'Tranche 2025', 'effectivity_date' => '2025-01-01', 'is_active' => false],
        ];

        foreach ($tranches as $tranche) {
            $tranche['created_at'] = Carbon::now();
            DB::table('salary_tranche')->insert($tranche);
        }

        // Get all tranche IDs
        $trancheIds = DB::table('salary_tranche')->pluck('id')->toArray();

        // --- 2. Create Salary Grades ---
        $grades = [];
        foreach ($trancheIds as $trancheId) {
            for ($sg = 1; $sg <= 3; $sg++) { // Example: 3 grades per tranche
                $grades[] = [
                    'tranche_id' => $trancheId,
                    'salary_grade' => $sg,
                    'created_at' => Carbon::now(),
                ];
            }
        }
        DB::table('salary_grade')->insert($grades);

        // --- 3. Create Salary Steps ---
        $gradeIds = DB::table('salary_grade')->pluck('id')->toArray();
        $steps = [];
        foreach ($gradeIds as $gradeId) {
            for ($step = 1; $step <= 5; $step++) { // Example: 5 steps per grade
                $steps[] = [
                    'grade_id' => $gradeId,
                    'step' => $step,
                    'salary_amount' => 20000 + ($gradeId * 1000) + ($step * 500), // example amount
                    'created_at' => Carbon::now(),
                ];
            }
        }
        DB::table('salary_step')->insert($steps);
    }
}
