<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use App\Models\Applicants;  
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
        public function index()
        {
            $applicants = Applicants::where('archived', false)->get();

            // 🔹 Generate next applicant number
            $latestApplicant = Applicants::latest('id')->first();
            $latestId = $latestApplicant ? $latestApplicant->id + 1 : 1;

            $year = now()->format('y'); // 2-digit year
            $generatedApplicantNo = 'TEMP-' . '11' . '-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);

            // 🔹 Pass both variables to the view
            return view('content.planning.applicants', compact('applicants', 'generatedApplicantNo'));
        }

    public function store(Request $request)
{
    $request->validate([
        'first_name'    => 'required',
        'last_name'     => 'required',
        'sex'           => 'required|in:Male,Female',
        'date_of_birth' => 'required|date',
        'date_applied'  => 'required|date',
        'status'        => 'required'
    ]);

    // === AUTO-GENERATE applicant_no (like your employee ID logic) ===
    $latestApplicant = Applicants::latest('id')->first();
    $latestId = $latestApplicant ? $latestApplicant->id + 1 : 1;

    // Format: TEMP-YY-#### (ex: TEMP-24-0001)
    $year = now()->format('y'); // two-digit year
    $applicantNo = 'TEMP-' . $year . '-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);

    // Optional: build username-like string (example of how to use initials)
    $middleInitial = $request->middle_name ? substr($request->middle_name, 0, 1) : '';
    $last4 = substr($applicantNo, -4);
    $username = strtolower(substr($request->first_name, 0, 1) . $middleInitial . $request->last_name . $last4);

    // Merge into data
    $data = $request->all();
    $data['applicant_no'] = $applicantNo;
    // $data['username'] = $username; // only if you want to save username too

    $applicant = Applicants::create($data);

    return response()->json([
        'success' => true,
        'applicant_no' => $applicant->applicant_no,
        // 'username' => $username // optional
    ]);
}

    public function update(Request $request, $id)
    {
        $applicants = Applicants::findOrFail($id);

        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'sex'           => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'date_applied'  => 'required|date',
            'status'        => 'required'
        ]);

        $applicants->update($request->all());

        return response()->json(['success' => true]);
    }

    public function archive($id)
    {
        $applicants = Applicants::findOrFail($id);
        $applicants->update(['archived' => true]); // mark as archived, not deleted

        return response()->json(['success' => true]);
    }
}
