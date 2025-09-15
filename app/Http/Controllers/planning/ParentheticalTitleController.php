<?php

namespace App\Http\Controllers\planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParentheticalTitle;

class ParentheticalTitleController extends Controller
{
    // Show the view with all position parentheticals
    public function index()
    {
        $parentheticalTitles = ParentheticalTitle::all();
        return view('content.planning.parenthetical-title', compact('parentheticalTitles'));
    }

    // Store a new position parenthetical
    public function store(Request $request)
    {
        $request->validate([
            'position_name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:50',
        ]);

        ParentheticalTitle::create([
            'position_name' => $request->position_name,
            'abbreviation' => $request->abbreviation,
        ]);

        return response()->json(['message' => 'Parenthetical Title created successfully']);
    }

    // Update an existing position parenthetical
    public function update(Request $request, $id)
    {
        $request->validate([
            'position_name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:50',
        ]);

        $parentheticalTitles = ParentheticalTitle::findOrFail($id);
        $parentheticalTitles->update([
            'position_name' => $request->position_name,
            'abbreviation' => $request->abbreviation,
        ]);

        return response()->json(['message' => 'Position Parenthetical updated successfully']);
    }

    // Delete a position parenthetical
    public function destroy($id)
    {
        $positionParenthetical = PositionParenthetical::findOrFail($id);
        $positionParenthetical->delete();

        return response()->json(['message' => 'Position Parenthetical deleted successfully']);
    }
}
