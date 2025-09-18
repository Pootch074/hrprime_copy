<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemNumber;
use App\Models\Applicant;

class UnfilledPositionsController extends Controller
{
  /**
   * Show all unfilled positions.
   */
  public function index()
  {
    $itemNumbers = ItemNumber::with(['position', 'salaryGrade', 'employmentStatus'])
      ->where('stature', 'unfilled')
      ->get();

    return view('content.planning.unfilled_positions.index', compact('itemNumbers'));
  }

  /**
   * Show a specific unfilled position and its applicants.
   */
  public function show($id)
  {
    $item = ItemNumber::with(['position', 'salaryGrade', 'employmentStatus', 'applicants'])
      ->findOrFail($id);

    $applicants = $item->applicants;

    return view('content.planning.unfilled_positions.show', compact('item', 'applicants'));
  }

  /**
   * Store applicant for a specific item number.
   */
  public function storeApplicant(Request $request, $id)
  {
    $request->validate([
      'first_name'     => 'required|string|max:100',
      'middle_name'    => 'nullable|string|max:100',
      'last_name'      => 'required|string|max:100',
      'extension_name' => 'nullable|string|max:50',
      'sex'            => 'required|in:Male,Female,Other',
      'date_of_birth'  => 'required|date',
      'date_applied'   => 'required|date',
      'status'         => 'nullable|string',
      'remarks'        => 'nullable|string|max:255',
      'date_hired'     => 'nullable|date',
    ]);

    $item = ItemNumber::findOrFail($id);
    $item->applicants()->create($request->all());

    // ✅ Refresh the page with success message
    return redirect()
      ->route('unfilled_positions.index', $id)
      ->with('success', 'Applicant added successfully!');
  }
}
