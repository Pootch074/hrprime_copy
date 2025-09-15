<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Section;
use App\Models\Division;

class UnitController extends Controller
{
  // GET SECTIONS BY DIVISION ID AND RETURN AS JSON
  public function getSectionsByDivision($id)
  {
    $sections = Section::where('division_id', $id)->get();
    return response()->json($sections);
  }

  // DISPLAY ALL UNITS WITH RELATED SECTION AND DIVISION DATA
  public function index()
  {
    $units = Unit::with('section.division')->get();
    $divisions = Division::all();
    return view('content.planning.unit', compact('units', 'divisions'));
  }

  // STORE A NEW UNIT RECORD
  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'abbreviation' => 'required|string|max:10',
      'division_id' => 'required|exists:divisions,id',
      'section_id' => 'required|exists:sections,id',
    ]);

    Unit::create([
      'name' => strtoupper($validated['name']),
      'abbreviation' => strtoupper($validated['abbreviation']),
      'section_id' => $validated['section_id'],
    ]);

    return response()->json(['success' => true]);
  }

  // UPDATE AN EXISTING UNIT
  public function update(Request $request, $id)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'abbreviation' => 'required|string|max:10',
      'division_id' => 'required|exists:divisions,id',
      'section_id' => 'required|exists:sections,id',
    ]);

    $unit = Unit::findOrFail($id);

    $unit->update([
      'name' => strtoupper($validated['name']),
      'abbreviation' => strtoupper($validated['abbreviation']),
      'section_id' => $validated['section_id'],
    ]);

    return response()->json(['success' => true]);
  }

  // DELETE A UNIT
  public function destroy($id)
  {
    $unit = Unit::findOrFail($id);
    $unit->delete();

    return response()->json(['success' => true]);
  }
}
