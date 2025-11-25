<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OutSlip;
use Illuminate\Support\Facades\Auth;


class OutSlipController extends Controller
{
  // Show list/dashboard
  public function index()
  {
    // Only fetch out slips with status Pending or Approved
    $outSlips = OutSlip::whereIn('status', ['Pending', 'Approved'])
      ->latest()
      ->get();

    return view('forms.outslip.index', compact('outSlips'));
  }
  public function create()
  {
    // Get the logged-in user
    $user = Auth::user();

    // Get the employee ID
    $empid = $user->employee_id; // assuming the column in users table is 'empid'

    // Pass it to the Blade view
    return view('forms.outslip.form', compact('empid'));
  }

  // Store new out slip
  public function store(Request $request)
  {
    $validated = $request->validate([
      'date' => 'required|date',
      'empid' => 'required|string|max:20',
      'destination' => 'required|string|max:255',
      'type_of_slip' => 'required|string|max:100',
      'purpose' => 'nullable|string|max:500',
    ]);

    $validated['status'] = 'Pending';
    $validated['approved_by'] = null;

    OutSlip::create($validated);

    return response()->json(['message' => 'Out Slip submitted successfully!']);
  }

  // Approve out slip
  public function approve($id)
  {
    $outSlip = OutSlip::findOrFail($id);
    $outSlip->update([
      'status' => 'Approved',
      'approved_by' => Auth::id(),
    ]);

    return response()->json(['message' => 'Out Slip approved successfully!']);
  }

  // Reject out slip
  public function reject($id)
  {
    $outSlip = OutSlip::findOrFail($id);
    $outSlip->update([
      'status' => 'Rejected',
      'approved_by' => Auth::id(),
    ]);

    return response()->json(['message' => 'Out Slip rejected.']);
  }
  public function print($id)
  {
    // Load slip with employee + division + section
    $slip = OutSlip::with([
      'employee.division',
      'employee.section'
    ])->findOrFail($id);

    // Load dropdowns
    $status = \App\Models\EmploymentStatus::all();
    $divisions = \App\Models\Division::all();
    $sections = \App\Models\Section::all();
    $salaryGrades = \App\Models\SalaryGrade::all();
    $positions = \App\Models\Position::all();

    return view('forms.outslip.print', compact(
      'slip',
      'status',
      'divisions',
      'sections',
      'salaryGrades',
      'positions'
    ));
  }
}
