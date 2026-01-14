<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\SalaryGrade;
use App\Models\SalaryTranche;
use App\Models\SalaryStep;
use App\Models\Division;
use App\Models\OfficeLocation;
use App\Models\EmploymentStatus;
use App\Models\Section;

class PositionController extends Controller
{
    // List all positions
        public function index()
            {
        return view('content.planning.position', [
            'positions'          => Position::with(['employmentStatus'])->get(),
            'salaryTranches'     => SalaryTranche::orderBy('tranche_name')->get(),
            'employmentStatuses' => EmploymentStatus::all(),
            'divisions'          => Division::all(),
            'officeLocations'    => OfficeLocation::all(),
             ]);
         }

        // Store new position
        public function store(Request $request)
        {
            $validated = $request->validate([
                'position_name'        => 'required|string|max:255',
                'abbreviation'         => 'required|string|max:50',
                'salary_tranche_id'    => 'nullable|exists:salary_tranche,id',
                'salary_grade_id'      => 'nullable|exists:salary_grades,id',
                'salary_step_id'       => 'nullable|exists:salary_step,id',
                'employment_status_id' => 'nullable|exists:employment_statuses,id',
                'division_id'          => 'nullable|exists:divisions,id',
                'section_id'           => 'nullable|exists:sections,id',
                'office_location_id'   => 'nullable|exists:office_locations,id',
            ]);

            $validated['position_name'] = strtoupper($validated['position_name']);
            $validated['abbreviation']  = strtoupper($validated['abbreviation']);

            $position = Position::create($validated);

            // Return the created position
            return response()->json([
                'success' => true,
                'id' => $position->id,
                'position_name' => $position->position_name,
                'abbreviation' => $position->abbreviation,
                'status' => $position->status ?? 'Active', // default if null
            ]);
        }
        // Update existing position
        public function update(Request $request, $id)
        {
            $validated = $request->validate([
                'position_name'        => 'required|string|max:255',
                'abbreviation'         => 'required|string|max:50',
                'salary_tranche_id'    => 'nullable|exists:salary_tranche,id',
                'salary_grade_id'      => 'nullable|exists:salary_grades,id',
                'salary_step_id'       => 'nullable|exists:salary_step,id',
                'employment_status_id' => 'nullable|exists:employment_statuses,id',
                'division_id'          => 'nullable|exists:divisions,id',
                'section_id'           => 'nullable|exists:sections,id',
                'office_location_id'   => 'nullable|exists:office_locations,id',
        ]);

        $validated['position_name'] = strtoupper($validated['position_name']);
        $validated['abbreviation']  = strtoupper($validated['abbreviation']);

        Position::findOrFail($id)->update($validated);

        return response()->json(['success' => true]);
        }

        // Delete position
        public function destroy($id)
        {
            Position::findOrFail($id)->delete();
            return response()->json(['success' => true]);
        }

        // Get sections for a division
        public function getSections($divisionId)
        {
        return Section::where('division_id', $divisionId)->select('id', 'name')
        ->get();
        }

        // Get salary grades by tranche
        public function getSalaryGrades($trancheId)
        {
        return SalaryGrade::where('tranche_id', $trancheId) 
        ->select('id', 'salary_grade')              
        ->orderBy('salary_grade')                   
        ->get();
        }

        // Get salary steps by grade
        public function getSalarySteps($gradeId)
        {
        return SalaryStep::where('grade_id', $gradeId)
        ->select('id', 'step', 'salary_amount as monthly_rate') 
        ->orderBy('step')
        ->get();
        }
        public function getMonthlyRate(Request $request)
        {
        $rate = SalaryStep::where('id', $request->step_id)
        ->where('grade_id', $request->grade_id)
        ->whereHas('grade', function ($q) use ($request) {
        $q->where('tranche_id', $request->tranche_id);
        })
        ->value('salary_amount'); // ← updated to match your column name

        return response()->json(['monthly_rate' => $rate]);
        }
        }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
