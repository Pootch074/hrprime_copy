<?php

namespace App\Http\Controllers;

use App\Models\Cpr;
use Illuminate\Http\Request;

class CprController extends Controller
{
  /**
   * Display a list of CPRs.
   */
  public function index()
  {
    $cprs = Cpr::latest()->get();
    return view('forms.cpr.index', compact('cprs'));
  }

  /**
   * Show modal/form to create a CPR.
   */
  public function create()
  {
    return view('forms.cpr.create');
  }

  /**
   * Store a new CPR record.
   */
  public function store(Request $request)
  {
    $request->validate([
      'rating_period_start' => 'required|date_format:Y-m',
      'semester' => 'required|string|in:1st Semester,2nd Semester',
    ]);

    // Convert YYYY-MM to YYYY-MM-01 for DATE column
    $ratingPeriodDate = $request->rating_period_start . '-01';

    // Save the CPR record
    Cpr::create([
      'rating_period_start' => $ratingPeriodDate,
      'semester' => $request->semester,
    ]);

    return redirect()->route('forms.cpr.index')
      ->with('success', 'CPR created successfully.');
  }

  public function update(Request $request, Cpr $cpr)
  {
    $request->validate([
      'rating_period_start' => 'required|date_format:Y-m',
      'semester' => 'required|string|in:1st Semester,2nd Semester',
    ]);

    // Convert YYYY-MM to YYYY-MM-01
    $ratingPeriodDate = $request->rating_period_start . '-01';

    $cpr->update([
      'rating_period_start' => $ratingPeriodDate,
      'semester' => $request->semester,
    ]);

    return redirect()->route('forms.cpr.index')
      ->with('success', 'CPR updated successfully.');
  }


  /**
   * Show a single CPR record.
   */
  public function show(Cpr $cpr)
  {
    return view('forms.cpr.show', compact('cpr'));
  }

  /**
   * Show form for editing a CPR.
   */
  public function edit(Cpr $cpr)
  {
    return view('forms.cpr.edit', compact('cpr'));
  }

  /**
   * Delete a CPR record.
   */
  public function destroy(Cpr $cpr)
  {
    $cpr->delete();

    return redirect()->route('forms.cpr.index')
      ->with('success', 'CPR deleted successfully.');
  }
}
