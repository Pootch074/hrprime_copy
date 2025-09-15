<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OfficeLocation;

class OfficeLocationController extends Controller
{
  public function index()
  {
    $officeLocations = OfficeLocation::all();
    return view('content.planning.office-location', compact('officeLocations'));
  }

  public function store(Request $request)
{
  $request->validate([
    'name' => 'required|string|max:255|unique:office_locations,name',
    'abbreviation' => 'nullable|string|max:50',
  ]);

  OfficeLocation::create([
    'name' => strtoupper(trim($request->name)),
    'abbreviation' => strtoupper(trim($request->abbreviation)),
  ]);

  return response()->json(['success' => true]);
}

public function update(Request $request, $id)
{
  $request->validate([
    'name' => 'required|string|max:255|unique:office_locations,name,' . $id,
    'abbreviation' => 'nullable|string|max:50',
  ]);

  $officeLocation = OfficeLocation::findOrFail($id);

  $officeLocation->update([
    'name' => strtoupper(trim($request->name)),
    'abbreviation' => strtoupper(trim($request->abbreviation)),
  ]);

  return response()->json(['success' => true]);
}
public function destroy($id)
{
  $location = OfficeLocation::findOrFail($id);
  $location->delete();

  return response()->json(['success' => true]);
}
}
