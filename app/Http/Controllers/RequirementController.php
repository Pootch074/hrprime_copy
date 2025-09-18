<?php

namespace App\Http\Controllers;

use App\Models\Requirement;
use App\Models\Position;
use Illuminate\Http\Request;

class RequirementController extends Controller
{
  // Store or update requirements for a position
  public function store(Request $request, $positionId)
  {
    try {
      $request->validate([
        'requirement' => 'required|array',
        'requirement.*' => 'string|max:255',
      ]);

      foreach ($request->requirement as $req) {
        Requirement::create([
          'position_id' => $positionId,
          'requirement' => $req,
        ]);
      }

      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      // \Log::error('Requirement store error: ' . $e->getMessage());
      return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
  }


  // Delete single requirement
  public function destroy($id)
  {
    $requirement = Requirement::findOrFail($id);
    $requirement->delete();

    return response()->json(['success' => true]);
  }

  // Fetch requirements by position
  public function getByPosition($positionId)
  {
    $position = Position::with('requirements')->findOrFail($positionId);

    return response()->json([
      'position' => $position->only(['id', 'position_name']),
      'requirements' => $position->requirements,
    ]);
  }
  // Update requirement
  public function update(Request $request, $id)
  {
    $request->validate([
      'requirement' => 'required|string|max:255',
    ]);

    $requirement = Requirement::findOrFail($id);
    $requirement->update([
      'requirement' => $request->requirement,
    ]);

    return response()->json(['success' => true]);
  }
}
