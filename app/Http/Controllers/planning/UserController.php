<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\EmploymentStatus;
use App\Models\Division;
use App\Models\Section;
use App\Models\ItemNumber;

class UserController extends Controller
{
    public function index()
    {
        $employees = User::with(['division', 'section', 'employmentStatus'])->get();
        return view('content.planning.list-of-employee', compact('employees'));
    }

    public function create()
    {
        $latestUser = User::where('employee_id', 'like', '11-%')->orderByDesc('id')->first();
        $number = $latestUser && preg_match('/11-(\d+)/', $latestUser->employee_id, $matches)
            ? intval($matches[1]) + 1
            : 1;

        $generatedEmployeeId = '11-' . str_pad($number, 4, '0', STR_PAD_LEFT);

        $employmentStatuses = EmploymentStatus::all();
        $divisions = Division::all();
        $salaryGrades = \App\Models\SalaryGrade::all();
        $positions = \App\Models\Position::all();

        return view('content.planning.registration-form', compact(
            'generatedEmployeeId',
            'employmentStatuses',
            'divisions',
            'salaryGrades',
            'positions'
        ));
    }

    // Status filters
    public function active() { return $this->filterByStatus('Active'); }
    public function retired() { return $this->filterByStatus('Retired'); }
    public function resigned() { return $this->filterByStatus('Resigned'); }

    private function filterByStatus($status)
    {
        $employees = User::where('status', $status)->with(['division','section','employmentStatus'])->get();
        return view('content.planning.list-of-employee', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|unique:users,employee_id',
            'first_name' => 'required',
            'last_name' => 'required',
            'employment_status' => 'required|exists:employment_statuses,id',
            'division' => 'required|exists:divisions,id',
            'section' => 'required|exists:sections,id',
            'password' => 'required|confirmed|min:6',
        ]);

        $firstName = ucwords(strtolower(trim($request->first_name)));
        $middleName = ucwords(strtolower(trim($request->middle_name)));
        $lastName = ucwords(strtolower(trim($request->last_name)));
        $extensionName = ucwords(strtolower(trim($request->extension_name)));

        $middleInitial = substr($middleName, 0, 1);
        $empIdLast4 = substr($request->employee_id, -4);
        $username = strtolower(substr($firstName, 0, 1) . $middleInitial . $lastName . $empIdLast4);

        User::create([
            'employee_id' => $request->employee_id,
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'extension_name' => $extensionName,
            'gender' => $request->gender,
            'employment_status_id' => $request->employment_status,
            'division_id' => $request->division,
            'section_id' => $request->section,
            'username' => $username,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('planning.registration-form')->with('success', 'Employee registered successfully!');
    }

    // ✅ Show employee details as JSON for AJAX
    public function show($id)
    {
        $employee = User::with([
            'division', 
            'section', 
            'employmentStatus',
            'csEligibilities', // if you have CS eligibility relation
            'family.children'  // if you have family relation
        ])->findOrFail($id);

        return response()->json($employee);
    }

    public function edits($id)
    {
        $employee = User::findOrFail($id);
        $divisions = Division::all();
        $sections = Section::where('division_id', $employee->division_id)->get();
        $employmentStatuses = EmploymentStatus::all();
        $itemNumbers = ItemNumber::all();

        return view('content.planning.employee-edit', compact('employee','divisions','sections','employmentStatuses','itemNumbers'));
    }

    public function update(Request $request, $id)
    {
        $employee = User::findOrFail($id);

        $request->validate([
            'employee_id' => 'required|unique:users,employee_id,' . $employee->id,
            'first_name' => 'required',
            'last_name' => 'required',
            'employment_status' => 'required',
            'division' => 'required',
            'section' => 'required',
            'password' => 'nullable|min:6',
        ]);

        $firstName = ucwords(strtolower(trim($request->first_name)));
        $middleName = ucwords(strtolower(trim($request->middle_name)));
        $lastName = ucwords(strtolower(trim($request->last_name)));
        $extensionName = ucwords(strtolower(trim($request->extension_name)));

        $username = $employee->employee_id != $request->employee_id
            ? strtolower(substr($firstName, 0, 1) . substr($middleName,0,1) . $lastName . substr($request->employee_id,-4))
            : $employee->username;

        $employee->update([
            'employee_id' => $request->employee_id,
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'extension_name' => $extensionName,
            'gender' => $request->gender,
            'employment_status_id' => $request->employment_status,
            'division_id' => $request->division,
            'section_id' => $request->section,
            'username' => $username,
            'email' => strtolower($request->email),
        ]);

        if($request->filled('password')) {
            $employee->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('planning.list-of-employee')->with('success','Employee updated successfully!');
    }

    public function getSections(Request $request)
    {
        $sections = Section::where('division_id', $request->division_id)->get();
        return response()->json($sections);
    }
}
