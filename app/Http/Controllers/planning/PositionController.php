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
use App\Models\PositionLevel;

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
            'positionLevels' => PositionLevel::orderBy('level_name')->get(),
             ]);
         }

        // Store new position
       public function store(Request $request)
    {
            $validated = $request->validate([
                'item_no'              => 'required|string|max:255',
                'office_location_id'   => 'nullable|exists:office_locations,id',
                'division_id'          => 'nullable|exists:divisions,id',
                'section_id'           => 'nullable|exists:sections,id',
                'program'              => 'nullable|string|max:255',
                'created_at'           => 'required|date',
                'position_name'        => 'required|string|max:255',
                'abbreviation'         => 'required|string|max:50',
                'parenthetical_title'  => 'nullable|string|max:255',
                'position_level_id'                => 'nullable|string|max:50',
                'salary_tranche_id'    => 'nullable|exists:salary_tranche,id', // ✅ Check table name
                'salary_grade_id'      => 'nullable|exists:salary_grades,id',
                'salary_step_id'       => 'nullable|exists:salary_step,id', // ✅ Check table name
                'monthly_rate'         => 'nullable|numeric',
                'designation'          => 'nullable|string|max:255',
                'special_order'        => 'nullable|string|max:255',
                'obsu'                 => 'nullable|string|max:255',
                'fund_source'          => 'nullable|string|max:255',
                'employment_status_id' => 'nullable|exists:employment_statuses,id',
                'type_of_request'      => 'nullable|string|max:50',
            ]);


        // Optional: uppercase some fields
        $validated['position_name'] = strtoupper($validated['position_name']);
        $validated['abbreviation']  = strtoupper($validated['abbreviation']);

        $position = Position::create($validated);

        return response()->json([
            'success' => true,
            'id' => $position->id,
            'position_name' => $position->position_name,
            'abbreviation' => $position->abbreviation,
            'status' => $position->status ?? 'Active',
        ]);
    }

        // Update existing position
        public function update(Request $request)
        {
            $request->validate([
                'id' => 'required|exists:positions,id',
                'position_name' => 'required',
                'abbreviation' => 'required',
            ]);

            $position = Position::findOrFail($request->id);

            $position->update($request->all());

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
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
