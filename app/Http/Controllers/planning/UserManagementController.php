<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Division;

class UserManagementController extends Controller
{
    // Show User Management Page
    public function index()
    {
        $divisions = Division::all();
        return view('content.planning.user-management', compact('divisions'));
    }

    // DataTable AJAX List
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with(['division', 'section'])->latest()->get();

            return datatables()->of($users)
                ->addIndexColumn()
                ->addColumn('name', fn($user) => strtoupper(trim($user->first_name . ' ' .
                    ($user->middle_name ? $user->middle_name . ' ' : '') .
                    $user->last_name .
                    ($user->extension_name ? ' ' . $user->extension_name : '')
                )))
                ->addColumn('division', fn($user) => $user->division?->name ?? '-')
                ->addColumn('section', fn($user) => $user->section?->name ?? '-')
                ->addColumn('is_active', fn($user) => $user->is_active ? 'Active' : 'Inactive')
                ->addColumn('action', function ($user) {
                    $btn  = '<a href="javascript:void(0)" data-id="'.$user->id.'" class="edit btn btn-primary btn-sm">Edit</a> ';
                    $btn .= $user->is_active
                        ? '<a href="javascript:void(0)" data-id="'.$user->id.'" class="toggle-status btn btn-danger btn-sm">Deactivate</a>'
                        : '<a href="javascript:void(0)" data-id="'.$user->id.'" class="toggle-status btn btn-success btn-sm">Activate</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    // Fetch User Data for Edit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Update User Division & Section
    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'division_id' => 'required',
        'section_id' => 'required',
        'is_active' => 'nullable|boolean',
        'password' => 'nullable|string|min:6',
    ]);

    // Update division, section, and status
    $user->update([
        'division_id' => $request->division_id,
        'section_id' => $request->section_id,
        'is_active' => $request->has('is_active'),
    ]);

    // Update password if provided
    if ($request->filled('password')) {
        $user->update(['password' => bcrypt($request->password)]);
    }

    // Map section abbreviations to roles
    $rolesMap = [
        'HRPPMS'     => 'HR-PLANNING',
        'HR-PAS'     => 'HR-PAS',
        'HR-LDS'     => 'HR-L&D',
        'HR-WELFARE' => 'HR-WELFARE',
    ];

    $section = $user->section;

    // Assign role based on section, default to EMPLOYEE
    $user->role = ($section && isset($rolesMap[strtoupper($section->abbreviation)]))
                ? $rolesMap[strtoupper($section->abbreviation)]
                : 'EMPLOYEE'; // default role if section not in the map

    $user->save();

    return response()->json(['success' => 'User updated successfully!']);
}


    // Activate User
    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = 1;
        $user->save();

        return response()->json(['success' => 'User has been activated successfully.']);
    }

    // Deactivate User
    public function deactivate(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($id);
        $user->is_active = 0;
        $user->deactivation_reason = $request->reason;
        $user->save();

        return response()->json(['success' => 'User has been deactivated successfully.']);
    }

    // Get Sections for a Division
    public function getSections($divisionId)
    {
        $division = Division::with('sections')->find($divisionId);
        $sections = $division ? $division->sections->map(fn($s) => ['id' => $s->id, 'name' => $s->name]) : [];
        return response()->json($sections);
    }
}
