<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\EmploymentStatus;
use App\Models\Division;
use App\Models\Section;
use App\Models\SalaryGrade;
use App\Models\Position;
use App\Models\Course; // ✅ Add this so Course model is recognized

class UserController extends Controller
{
  /*==============================
        EMPLOYEE LISTS
    ==============================*/

  public function index()
  {
    $employees = User::with(['division', 'section', 'employmentStatus'])->get();
    return view('content.planning.list-of-employee', compact('employees'));
  }

  public function active()
  {
    $employees = User::where('status', 'Active')
      ->with(['division', 'section', 'employmentStatus'])
      ->get();

    return view('content.planning.list-of-employee', compact('employees'));
  }

  public function retired()
  {
    $employees = User::where('status', 'Retired')
      ->with(['division', 'section', 'employmentStatus'])
      ->get();

    return view('content.planning.list-of-employee', compact('employees'));
  }

  public function resigned()
  {
    $employees = User::where('status', 'Resigned')
      ->with(['division', 'section', 'employmentStatus'])
      ->get();

    return view('content.planning.list-of-employee', compact('employees'));
  }

  /*==============================
        CREATE / STORE EMPLOYEE
    ==============================*/
  public function create()
  {
    // Generate the next employee ID
    $latestUser = User::where('employee_id', 'like', '11-%')
      ->orderByDesc('id')
      ->first();

    $number = ($latestUser && preg_match('/11-(\d+)/', $latestUser->employee_id, $matches))
      ? intval($matches[1]) + 1
      : 1;

    $generatedEmployeeId = '11-' . str_pad($number, 4, '0', STR_PAD_LEFT);

    return view('content.planning.registration-form', [
      'generatedEmployeeId' => $generatedEmployeeId,
      'employmentStatuses'  => EmploymentStatus::all(),
      'divisions'           => Division::all(),
      'salaryGrades'        => SalaryGrade::all(),
      'positions'           => Position::all(),
    ]);
  }

  public function store(Request $request)
  {
    $request->validate([
      'employee_id'       => 'required|unique:users,employee_id',
      'first_name'        => 'required',
      'last_name'         => 'required',
      'employment_status' => 'required|exists:employment_statuses,id',
      'division'          => 'required|exists:divisions,id',
      'section'           => 'required|exists:sections,id',
      'password'          => 'required|confirmed|min:6',
    ]);

    // Capitalize inputs
    $firstName     = ucwords(strtolower(trim($request->first_name)));
    $middleName    = ucwords(strtolower(trim($request->middle_name)));
    $lastName      = ucwords(strtolower(trim($request->last_name)));
    $extensionName = ucwords(strtolower(trim($request->extension_name)));

    // Generate username
    $middleInitial = substr($middleName, 0, 1);
    $empIdLast4    = substr($request->employee_id, -4);
    $username      = strtolower(substr($firstName, 0, 1) . $middleInitial . $lastName . $empIdLast4);

    // Create user
    User::create([
      'employee_id'          => $request->employee_id,
      'first_name'           => $firstName,
      'middle_name'          => $middleName,
      'last_name'            => $lastName,
      'extension_name'       => $extensionName,
      'gender'               => $request->gender,
      'employment_status_id' => $request->employment_status,
      'division_id'          => $request->division,
      'section_id'           => $request->section,
      'username'             => $username,
      'email'                => strtolower($request->email),
      'password'             => Hash::make($request->password),
    ]);

    return redirect()->route('employee.registration-form')->with('success', 'Employee registered successfully!');
  }

  /*==============================
        SHOW / EDIT / UPDATE EMPLOYEE
    ==============================*/
  public function show($id)
  {
    $employee = User::with(['division', 'section', 'employmentStatus'])->findOrFail($id);
    return view('content.planning.employee-view', compact('employee'));
  }

  public function edit($id)
  {
    $employee           = User::findOrFail($id);
    $divisions          = Division::all();
    $sections           = Section::where('division_id', $employee->division_id)->get();
    $employmentStatuses = EmploymentStatus::all();

    return view('content.planning.employee-edit', compact('employee', 'divisions', 'sections', 'employmentStatuses'));
  }

  public function update(Request $request, $id)
  {
    $employee = User::findOrFail($id);

    $request->validate([
      'employee_id'       => 'required|unique:users,employee_id,' . $employee->id,
      'first_name'        => 'required',
      'last_name'         => 'required',
      'employment_status' => 'required',
      'division'          => 'required',
      'section'           => 'required',
      'password'          => 'nullable|min:6',
    ]);

    // Capitalize inputs
    $firstName     = ucwords(strtolower(trim($request->first_name)));
    $middleName    = ucwords(strtolower(trim($request->middle_name)));
    $lastName      = ucwords(strtolower(trim($request->last_name)));
    $extensionName = ucwords(strtolower(trim($request->extension_name)));

    // Regenerate username if employee_id changed
    $username = $employee->employee_id != $request->employee_id
      ? strtolower(substr($firstName, 0, 1) . substr($middleName, 0, 1) . $lastName . substr($request->employee_id, -4))
      : $employee->username;

    // Update employee
    $employee->update([
      'employee_id'          => $request->employee_id,
      'first_name'           => $firstName,
      'middle_name'          => $middleName,
      'last_name'            => $lastName,
      'extension_name'       => $extensionName,
      'gender'               => $request->gender,
      'employment_status_id' => $request->employment_status,
      'division_id'          => $request->division,
      'section_id'           => $request->section,
      'username'             => $username,
      'email'                => strtolower($request->email),
    ]);

    if (!empty($request->password)) {
      $employee->update(['password' => Hash::make($request->password)]);
    }

    return redirect()->route('employee.list-of-employee')->with('success', 'Employee updated successfully!');
  }

  /*==============================
        UTILITY METHODS
    ==============================*/
  public function getSections(Request $request)
  {
    $sections = Section::where('division_id', $request->division_id)->get();
    return response()->json($sections);
  }

  /*==============================
        EMPLOYEE PROFILE & COURSES
    ==============================*/


  public function enrollCourse($id)
  {
    $user = auth()->user();
    if (!$user) {
      return redirect()->route('login')->with('error', 'Please login first.');
    }

    $course = Course::findOrFail($id);

    // Check if user already enrolled
    if (!$user->courses()->where('course_id', $id)->exists()) {
      $user->courses()->attach($course->id, ['status' => 'Ongoing']);
    }

    return redirect()->route('employee.courses')
      ->with('success', 'You have successfully enrolled in ' . $course->title . '.');
  }
}
