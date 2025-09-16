<?php

namespace App\Http\Controllers;

use App\Models\ItemNumber;
use App\Models\Position;
use App\Models\SalaryGrade;
use App\Models\EmploymentStatus;
use Illuminate\Http\Request;

class ItemNumberController extends Controller
{
  public function index()
  {
    $itemNumbers = ItemNumber::with(['position', 'salaryGrade', 'employmentStatus'])->get();
    $positions = Position::all();
    $salaryGrades = SalaryGrade::all();
    $employmentStatuses = EmploymentStatus::all();

    return view('content.planning.item-numbers', compact(
      'itemNumbers',
      'positions',
      'salaryGrades',
      'employmentStatuses'
    ));
  }

  public function store(Request $request)
  {
    $request->validate([
      'item_number' => 'required|unique:item_numbers,item_number',
      'position_id' => 'required|exists:positions,id',
      'salary_grade_id' => 'required|exists:salary_grades,id',
      'employment_status_id' => 'required|exists:employment_statuses,id',
    ]);

    ItemNumber::create([
      'item_number' => $request->item_number,
      'position_id' => $request->position_id,
      'salary_grade_id' => $request->salary_grade_id,
      'employment_status_id' => $request->employment_status_id,
      'status' => 'active',
    ]);

    return response()->json(['success' => true, 'message' => 'Item Number added successfully']);
  }

  public function update(Request $request, $id)
  {
    $item = ItemNumber::findOrFail($id);

    $request->validate([
      'item_number' => 'required|unique:item_numbers,item_number,' . $id,
      'position_id' => 'required|exists:positions,id',
      'salary_grade_id' => 'required|exists:salary_grades,id',
      'employment_status_id' => 'required|exists:employment_statuses,id',
    ]);

    $item->update([
      'item_number' => $request->item_number,
      'position_id' => $request->position_id,
      'salary_grade_id' => $request->salary_grade_id,
      'employment_status_id' => $request->employment_status_id,
      'status' => $request->status ?? 'active',
    ]);

    return response()->json(['success' => true, 'message' => 'Item Number updated successfully']);
  }

  public function destroy($id)
  {
    $item = ItemNumber::findOrFail($id);
    $item->delete();

    return response()->json(['success' => true, 'message' => 'Item Number deleted successfully']);
  }
}
