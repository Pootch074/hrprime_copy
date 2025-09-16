<?php

namespace App\Http\Controllers\learning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Scholarship;

class ScholarshipController extends Controller
{
  public function index()
  {
    $scholarships = Scholarship::all();
    return view('content.learning.scholarship', compact('scholarships')); //
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'dateFrom' => 'required|date',
    ]);

    $scholarship = new Scholarship();
    $scholarship->title = $validated['title'];
    $scholarship->date_from = $validated['dateFrom'];
    $scholarship->status = 'Pending';
    $scholarship->added_by = $request->input('addedBy') ?? 'System';
    $scholarship->date_added = $request->input('dateAdded');
    $scholarship->save();

    return response()->json(['message' => 'Scholarship added successfully!']);
  }
  public function updateStatus(Request $request, $id)
  {
    $request->validate([
      'status' => 'required|in:Pending,Active,Inactive'
    ]);

    $scholarship = Scholarship::findOrFail($id);
    $scholarship->status = $request->status;
    $scholarship->save();

    return response()->json(['message' => 'Status updated successfully.']);
  }
}
