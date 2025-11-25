<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
  // 1️⃣ List all leaves
  public function index()
  {
    $leaves = Leave::with('employee')->latest()->get();
    return view('forms.leaves.index', compact('leaves'));
  }

  // 2️⃣ Show create leave form
  public function create()
  {
    $employees = User::all();
    return view('forms.leaves.create', compact('employees'));
  }

  // 3️⃣ Store a new leave
  public function store(Request $request)
  {
    $validated = $request->validate([
      'empid' => 'required',
      'leave_type' => 'required|string',
      'leave_type_specify' => 'nullable|string',

      // Vacation
      'leave_spent_vac_specify' => 'nullable|string',
      'abroad_specify' => 'nullable|string',

      // Sick
      'leave_spent_sick_specify' => 'nullable|string',
      'hospital_specify' => 'nullable|string',
      'at_home_specify' => 'nullable|string',

      // Study
      'leave_study_specify' => 'nullable|string',

      'leave_no_wdays' => 'required|numeric',
      'from_date' => 'required|date',
      'to_date' => 'required|date|after_or_equal:from_date',
      'other_purpose' => 'nullable|string',
      'datefiled' => 'required|date',
    ]);

    // Map leave_spent and leave_specify dynamically based on type
    $leaveData = $validated;

    switch ($validated['leave_type']) {
      case 'Vacation':
      case 'Special Privilege':
        $leaveData['leave_spent'] = $validated['leave_spent_vac_specify'] ?? null;
        $leaveData['leave_specify'] = $validated['abroad_specify'] ?? null;
        break;

      case 'Sick':
        $leaveData['leave_spent'] = $validated['leave_spent_sick_specify'] ?? null;
        $leaveData['leave_specify'] = $validated['hospital_specify'] ?? $validated['at_home_specify'] ?? null;
        break;

      case 'Study':
        $leaveData['leave_spent'] = $validated['leave_study_specify'] ?? null;
        $leaveData['leave_specify'] = null;
        break;

      case 'Others':
        $leaveData['leave_spent'] = null;
        $leaveData['leave_specify'] = $validated['leave_type_specify'] ?? null;
        break;

      default:
        $leaveData['leave_spent'] = null;
        $leaveData['leave_specify'] = null;
    }

    // Store in database
    Leave::create($leaveData);

    return redirect()->route('forms.leaves.index')->with('success', 'Leave applied successfully.');
  }


  // 4️⃣ Show a single leave
  public function show($id)
  {
    $leave = Leave::with('employee')->findOrFail($id);
    return view('forms.leaves.show', compact('leave'));
  }

  // 5️⃣ Show edit form
  public function edit($id)
  {
    $leave = Leave::findOrFail($id);
    $employees = User::all();
    return view('forms.leaves.edit', compact('leave', 'employees'));
  }

  // 6️⃣ Update leave with all fields
  public function update(Request $request, $id)
  {
    $leave = Leave::findOrFail($id);

    // Validate input
    $validated = $request->validate([
      'empid' => 'required',
      'leave_type' => 'required|string',
      'leave_type_specify' => 'nullable|string',
      'leave_no_wdays' => 'required|numeric',
      'from_date' => 'required|date',
      'to_date' => 'required|date|after_or_equal:from_date',
      'other_purpose' => 'nullable|string',
      // Optional fields depending on leave type
      'leave_spent_vac_specify' => 'nullable|string',
      'abroad_specify' => 'nullable|string',
      'leave_spent_sick_specify' => 'nullable|string',
      'hospital_specify' => 'nullable|string',
      'leave_study_specify' => 'nullable|string',
    ]);

    // Map extra fields to the database fields
    $leave->empid = $validated['empid'];
    $leave->leave_type = $validated['leave_type'];
    $leave->leave_type_specify = $validated['leave_type_specify'] ?? null;
    $leave->from_date = $validated['from_date'];
    $leave->to_date = $validated['to_date'];
    $leave->leave_no_wdays = $validated['leave_no_wdays'];
    $leave->other_purpose = $validated['other_purpose'] ?? null;

    // Handle Vacation / Special Privilege Leave
    if (in_array($validated['leave_type'], ['Vacation', 'Special Privilege'])) {
      $leave->leave_spent = $validated['leave_spent_vac_specify'] ?? null;
      $leave->leave_specify = $validated['abroad_specify'] ?? null;
    }

    // Handle Sick Leave
    if ($validated['leave_type'] === 'Sick') {
      $leave->leave_spent = $validated['leave_spent_sick_specify'] ?? null;
      $leave->leave_specify = $validated['hospital_specify'] ?? null;
    }

    // Handle Study Leave
    if ($validated['leave_type'] === 'Study') {
      $leave->leave_spent = $validated['leave_study_specify'] ?? null;
      $leave->leave_specify = null;
    }

    $leave->save();

    // Redirect with success message
    return redirect()->route('forms.leaves.index')->with('success', 'Leave updated successfully.');
  }

  // 7️⃣ Delete leave
  public function destroy($id)
  {
    $leave = Leave::findOrFail($id);
    $leave->delete();
    return redirect()->route('forms.leaves.index')->with('success', 'Leave deleted successfully.');
  }

  // 8️⃣ Approve leave
  public function approve($id)
  {
    $leave = Leave::findOrFail($id);
    $leave->status = 'Approved';
    $leave->leave_approved_by = Auth::id();
    $leave->date_approved = now();
    $leave->save();

    return redirect()->route('forms.leaves.index')->with('success', 'Leave approved.');
  }

  // 9️⃣ Reject leave
  public function reject(Request $request, $id)
  {
    $leave = Leave::findOrFail($id);
    $leave->status = 'Rejected';
    $leave->leave_remarks = $request->leave_remarks ?? 'No remarks';
    $leave->leave_approved_by = Auth::id();
    $leave->date_approved = now();
    $leave->save();

    return redirect()->route('forms.leaves.index')->with('success', 'Leave rejected.');
  }


  public function print($id)
  {
    // Load slip with employee + division + section
    $leave = Leave::with([
      'employee.division',
      'employee.section',
      'employee.position'
    ])->findOrFail($id);


    // Load dropdowns
    $status = \App\Models\EmploymentStatus::all();
    $divisions = \App\Models\Division::all();
    $sections = \App\Models\Section::all();
    $salaryGrades = \App\Models\SalaryGrade::all();
    $positions = \App\Models\Position::all();

    return view('forms.leaves.print', compact(
      'leave',
      'status',
      'divisions',
      'sections',
      'salaryGrades',
      'positions'
    ));
  }
}
