<?php

namespace App\Http\Controllers;

use App\Models\CprEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cpr;

class CprEmployeeController extends Controller
{

  public function index()
  {
    // Get all CPR Employees for the table
    $cprEmployees = CprEmployee::latest()->get();

    // Get all CPRs for the dropdown
    $cprs = Cpr::latest()->get();

    // Get logged-in user ID
    $userId = Auth::id(); // or Auth::user()->employee_id if using employee_id field

    return view('forms.cpremployee.index', compact('cprEmployees', 'cprs', 'userId'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'employee_id' => 'required|integer',
      'cpr_id'      => 'required|integer',
      'rating'      => 'required|numeric|min:0|max:100',
    ]);

    CprEmployee::create($validated);

    return back()->with('success', 'CPR Employee added successfully.');
  }

  public function destroy($id)
  {
    $record = CprEmployee::findOrFail($id);
    $record->delete();

    return back()->with('success', 'CPR Employee deleted successfully.');
  }
}
