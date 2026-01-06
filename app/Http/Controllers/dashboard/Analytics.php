<?php

namespace App\Http\Controllers\dashboard;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class Analytics extends Controller
{
    public function index(Request $request)
    {
        // ================= FILTER INPUTS =================
        $filterType = $request->input('filter_type', 'year');
        $year       = $request->input('year', date('Y'));
        $month      = $request->input('month'); // YYYY-MM
        $quarter    = $request->input('quarter');

        // ================= BASE USER QUERY =================
        $usersQuery = User::query();

        // Apply filter
        if ($filterType === 'month' && $month) {
            $usersQuery->whereYear('users.created_at', substr($month, 0, 4))
                    ->whereMonth('users.created_at', substr($month, 5, 2));
        } elseif ($filterType === 'quarter' && $quarter) {
            $startMonth = ($quarter - 1) * 3 + 1;
            $endMonth   = $startMonth + 2;
            $usersQuery->whereYear('users.created_at', $year)
                    ->whereBetween(DB::raw('MONTH(users.created_at)'), [$startMonth, $endMonth]);
        } else {
            $usersQuery->whereYear('users.created_at', $year);
        }

        // ================= BASIC COUNTS =================
        $overallEmployees = $usersQuery->count();
        $activeEmployees  = (clone $usersQuery)->where('status', 'Active')->count();
        $male   = (clone $usersQuery)->where('gender', 'Male')->count();
        $female = (clone $usersQuery)->where('gender', 'Female')->count();

        // ================= DIVISIONS =================
        $divisions = (clone $usersQuery)
            ->join('divisions', 'users.division_id', '=', 'divisions.id')
            ->select('divisions.abbreviation', DB::raw('COUNT(*) as total'))
            ->groupBy('divisions.abbreviation')
            ->orderByDesc('total')
            ->pluck('total', 'abbreviation')
            ->toArray();

        // ================= OFFICE LOCATIONS =================
        $office_locations = (clone $usersQuery)
            ->join('office_locations', 'users.office_location', '=', 'office_locations.id')
            ->select('office_locations.abbreviation', DB::raw('COUNT(*) as total'))
            ->groupBy('office_locations.abbreviation')
            ->orderByDesc('total')
            ->pluck('total', 'office_locations.abbreviation')
            ->toArray();

        // ================= AVERAGE AGE =================
        $averageAge = round(
            (clone $usersQuery)
                ->selectRaw('AVG(TIMESTAMPDIFF(YEAR, birthday, CURDATE()))')
                ->value(DB::raw('AVG(TIMESTAMPDIFF(YEAR, birthday, CURDATE()))')),
            1
        );

        // ================= AGE GROUPS =================
        $ageGroups = [
            '20-29' => [20, 29],
            '30-39' => [30, 39],
            '40-49' => [40, 49],
            '50+'   => [50, 150],
        ];

        $maleAgeCounts = [];
        $femaleAgeCounts = [];

        foreach ($ageGroups as [$min, $max]) {
            $maleAgeCounts[] = (clone $usersQuery)
                ->where('gender', 'Male')
                ->whereRaw("TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN ? AND ?", [$min, $max])
                ->count();

            $femaleAgeCounts[] = (clone $usersQuery)
                ->where('gender', 'Female')
                ->whereRaw("TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN ? AND ?", [$min, $max])
                ->count();
        }

        // ================= EMPLOYMENT STATUS =================
        $employmentStats = (clone $usersQuery)
            ->leftJoin('employment_statuses', 'users.employment_status_id', '=', 'employment_statuses.id')
            ->select(
                DB::raw("COALESCE(employment_statuses.name, 'Unspecified') as status"),
                DB::raw("SUM(CASE WHEN users.gender = 'Male' THEN 1 ELSE 0 END) as male"),
                DB::raw("SUM(CASE WHEN users.gender = 'Female' THEN 1 ELSE 0 END) as female"),
                DB::raw("COUNT(users.id) as total")
            )
            ->groupBy(DB::raw("COALESCE(employment_statuses.name, 'Unspecified')"))
            ->orderByDesc('total')
            ->get();

        $statuses          = $employmentStats->pluck('status')->toArray();
        $malePerStatus     = $employmentStats->pluck('male')->toArray();
        $femalePerStatus   = $employmentStats->pluck('female')->toArray();
        $employment_status = $employmentStats->pluck('total', 'status')->toArray();

        // ================= RETURN JSON IF AJAX =================
        if ($request->ajax()) {
            return response()->json([
                'overallEmployees' => $overallEmployees,
                'activeEmployees'  => $activeEmployees,
                'male' => $male,
                'female' => $female,
                'divisions' => $divisions,
                'office_locations' => $office_locations,
                'averageAge' => $averageAge,
                'ageGroups' => array_keys($ageGroups),
                'maleAgeCounts' => $maleAgeCounts,
                'femaleAgeCounts' => $femaleAgeCounts,
                'statuses' => $statuses,
                'malePerStatus' => $malePerStatus,
                'femalePerStatus' => $femalePerStatus,
                'employment_status' => $employment_status
            ]);
        }

        // ================= RETURN VIEW =================
        return view('content.planning.dashboard', compact(
            'overallEmployees',
            'activeEmployees',
            'male',
            'female',
            'divisions',
            'office_locations',
            'averageAge',
            'ageGroups',
            'maleAgeCounts',
            'femaleAgeCounts',
            'statuses',
            'malePerStatus',
            'femalePerStatus',
            'employment_status',
            'filterType',
            'year',
            'month',
            'quarter'
        ));
    }
}
