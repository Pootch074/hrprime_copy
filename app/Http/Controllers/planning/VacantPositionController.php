<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VacantPosition;

class VacantPositionController extends Controller
{
    public function index()
        {
            // Eager load qualification and its users (qualified staff)
            $vacantPositions = VacantPosition::with('qualification.users')->get();

            return view('content.planning.vacant-position', compact('vacantPositions'));
        }

    public function store(Request $request)
{
    $request->validate([
        'position_id' => 'required|exists:positions,id',
        'qualification_id' => 'nullable|exists:qualifications,id',
    ]);

    VacantPosition::create([
        'position_id' => $request->position_id,
        'qualification_id' => $request->qualification_id,
    ]);

    return response()->json(['success' => true, 'message' => 'Vacant position posted successfully.']);
}
}
