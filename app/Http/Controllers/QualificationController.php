<?php

namespace App\Http\Controllers;

use App\Models\Qualification;
use App\Models\Position;
use Illuminate\Http\Request;

class QualificationController extends Controller
{
  // Store new qualifications for a position
  public function store(Request $request, $positionId)
  {
    $request->validate([
      'qualification'   => 'required|array',
      'qualification.*' => 'string|max:255',
    ]);

    foreach ($request->qualification as $qual) {
      Qualification::create([
        'position_id' => $positionId,
        'title'       => $qual,   // ✅ save qualification title
      ]);
    }

    return response()->json(['success' => true]);
  }

  // Fetch qualifications by position
  public function getByPosition($positionId)
  {
    $position = Position::with('qualifications')->findOrFail($positionId);

    return response()->json([
      'position'       => $position->only(['id', 'position_name']),
      'qualifications' => $position->qualifications,
    ]);
  }

  // Update single qualification
  public function update(Request $request, $id)
  {
    $request->validate([
      'title' => 'required|string|max:255',
    ]);

    $qualification = Qualification::findOrFail($id);
    $qualification->update([
      'title' => $request->title,
    ]);

    return response()->json(['success' => true]);
  }

  // Remove qualification (one-to-many delete)
  public function destroy($id)
  {
    $qualification = Qualification::findOrFail($id);
    $qualification->delete();

    return response()->json(['success' => true]);
  }
}
