<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmploymentStatus;

class EmploymentStatusController extends Controller
{
  public function index()
  {
    $employmentStatuses = EmploymentStatus::all();
    return view('content.planning.employment-status', compact('employmentStatuses'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255|unique:employment_statuses,name',
      'abbreviation' => 'nullable|string|max:50',
    ]);

    // Convert to UPPERCASE before saving
    $validated['name'] = strtoupper($validated['name']);
    $validated['abbreviation'] = strtoupper($validated['abbreviation']);

    EmploymentStatus::create($validated);

    return response()->json(['success' => true]);
  }

  public function update(Request $request, $id)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255|unique:employment_statuses,name,' . $id,
      'abbreviation' => 'nullable|string|max:50',
    ]);

    // Convert to UPPERCASE before updating
    $validated['name'] = strtoupper($validated['name']);
    $validated['abbreviation'] = strtoupper($validated['abbreviation']);

    EmploymentStatus::findOrFail($id)->update($validated);

    return response()->json(['success' => true]);
  }

  public function destroy($id)
  {
    EmploymentStatus::findOrFail($id)->delete();
    return response()->json(['success' => true]);
  }
}
