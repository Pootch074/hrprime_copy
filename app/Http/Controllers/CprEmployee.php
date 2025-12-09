<?php

namespace App\Http\Controllers;

use App\Models\CprEmployee;
use Illuminate\Http\Request;

class CPREmployeeController extends Controller
{
  /**
   * Display a listing of the CPR Employees.
   */
  public function index()
  {
    $records = CprEmployee::with('employee', 'cpr')->get();
    return response()->json($records);
  }

  /**
   * Store a newly created CPR Employee.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'employee_id' => 'required|integer',
      'cpr_id'      => 'required|integer',
      'rating'      => 'nullable|numeric',
    ]);

    $cprEmployee = CprEmployee::create($validated);

    return response()->json([
      'message' => 'CPR Employee added successfully.',
      'data' => $cprEmployee
    ]);
  }

  /**
   * Display a single CPR Employee record.
   */
  public function show($id)
  {
    $record = CprEmployee::with('employee', 'cpr')->findOrFail($id);
    return response()->json($record);
  }

  /**
   * Update a CPR Employee record.
   */
  public function update(Request $request, $id)
  {
    $validated = $request->validate([
      'employee_id' => 'sometimes|integer',
      'cpr_id'      => 'sometimes|integer',
      'rating'      => 'nullable|numeric',
    ]);

    $record = CprEmployee::findOrFail($id);
    $record->update($validated);

    return response()->json([
      'message' => 'CPR Employee updated successfully.',
      'data' => $record
    ]);
  }

  /**
   * Remove a CPR Employee record.
   */
  public function destroy($id)
  {
    $record = CprEmployee::findOrFail($id);
    $record->delete();

    return response()->json([
      'message' => 'CPR Employee deleted successfully.'
    ]);
  }
}
