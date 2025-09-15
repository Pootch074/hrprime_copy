<?php

namespace App\Http\Controllers\planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PositionLevel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PositionLevelController extends Controller
{
  // Show all position levels
  public function index()
  {
    $positionLevels = PositionLevel::orderBy('level_rank')->get();
    return view('content.planning.position-level', compact('positionLevels'));
  }

  // Store a new position level
  public function store(Request $request)
  {
    $request->validate([
      'level_name'    => 'required|string|max:255',
      'abbreviation'  => 'nullable|string|max:50',
      'level_rank'    => 'nullable|integer|min:1',
    ]);

    PositionLevel::create([
      'level_name'   => strtoupper(trim($request->level_name)),
      'abbreviation' => strtoupper(trim($request->abbreviation ?? '')),
      'level_rank'   => $request->level_rank,
    ]);

    return response()->json(['message' => 'Position Level created successfully']);
  }

  // Update an existing position level
  public function update(Request $request, $id)
  {
    $request->validate([
      'level_name'    => 'required|string|max:255',
      'abbreviation'  => 'nullable|string|max:50',
      'level_rank'    => 'nullable|integer|min:1',
    ]);

    $positionLevel = PositionLevel::findOrFail($id);

    $positionLevel->update([
      'level_name'   => strtoupper(trim($request->level_name)),
      'abbreviation' => strtoupper(trim($request->abbreviation ?? '')),
      'level_rank'   => $request->level_rank,
    ]);

    return response()->json(['message' => 'Position Level updated successfully']);
  }

  // Delete a position level
  public function destroy($id)
  {
    try {
      $positionLevel = PositionLevel::findOrFail($id);

      if ($positionLevel->positions()->exists()) {
        return response()->json([
          'message' => 'Cannot delete. This level is assigned to one or more positions.'
        ], 400);
      }

      $positionLevel->delete();

      return response()->json(['message' => 'Position Level deleted successfully']);
    } catch (ModelNotFoundException $e) {
      return response()->json(['message' => 'Position Level not found'], 404);
    }
  }
}
