<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\Requirement;
use App\Models\Qualification;

class PositionController extends Controller
{
  /**
   * Display a listing of positions.
   */
  public function index()
  {
    $positions = Position::with(['requirements', 'qualifications'])
      ->orderBy('position_name')
      ->get();

    return view('content.planning.position', compact('positions'));
  }

  /**
   * Store a newly created position.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'position_name' => 'required|string|max:255',
      'abbreviation'  => 'required|string|max:50',
    ]);

    // Default to active
    $validated['status'] = 'active';

    Position::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Position created successfully.'
    ]);
  }

  /**
   * Update an existing position.
   */
  public function update(Request $request, $id)
  {
    $validated = $request->validate([
      'position_name' => 'required|string|max:255',
      'abbreviation'  => 'required|string|max:50',
      'status'        => 'required|in:active,inactive',
    ]);

    // Force uppercase
    $validated['position_name'] = strtoupper($validated['position_name']);
    $validated['abbreviation']  = strtoupper($validated['abbreviation']);

    $position = Position::findOrFail($id);
    $position->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Position updated successfully.'
    ]);
  }

  /**
   * Remove a position.
   */
  public function destroy($id)
  {
    $position = Position::findOrFail($id);
    $position->delete();

    return response()->json([
      'success' => true,
      'message' => 'Position deleted successfully.'
    ]);
  }

  // --------------------------
  // REQUIREMENTS
  // --------------------------
  public function getRequirements($id)
  {
    return Requirement::where('position_id', $id)->get();
  }

  public function storeRequirement(Request $request, $id)
  {
    $request->validate([
      'requirement' => 'required|string|max:255'
    ]);

    Requirement::create([
      'position_id' => $id,
      'requirement' => $request->requirement,
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Requirement added successfully.'
    ]);
  }

  public function deleteRequirement($id)
  {
    Requirement::findOrFail($id)->delete();

    return response()->json([
      'success' => true,
      'message' => 'Requirement deleted successfully.'
    ]);
  }

  // --------------------------
  // QUALIFICATIONS
  // --------------------------
  public function getQualifications($id)
  {
    return Qualification::where('position_id', $id)->get();
  }

  public function storeQualification(Request $request, $id)
  {
    $request->validate([
      'qualification' => 'required|string|max:255'
    ]);

    Qualification::create([
      'position_id' => $id,
      'qualification' => $request->qualification,
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Qualification added successfully.'
    ]);
  }

  public function deleteQualification($id)
  {
    Qualification::findOrFail($id)->delete();

    return response()->json([
      'success' => true,
      'message' => 'Qualification deleted successfully.'
    ]);
  }
}
