<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FamilyBackground;

class FamilyBackgroundController extends Controller
{
    /**
     * Show the create form.
     */
        public function create()
        {
            return view('content.planning.profile.family-background', compact('familyBackground'));
        }

    /**
     * Store a new family background.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            // spouse
            's_lname' => 'nullable|string|max:255',
            's_fname' => 'nullable|string|max:255',
            's_mname' => 'nullable|string|max:255',
            's_ext'   => 'nullable|string|max:50',
            's_occ'   => 'nullable|string|max:255',
            's_emp'   => 'nullable|string|max:255',
            's_addr'  => 'nullable|string|max:500',

            // father
            'f_lname' => 'nullable|string|max:255',
            'f_fname' => 'nullable|string|max:255',
            'f_mname' => 'nullable|string|max:255',
            'f_ext'   => 'nullable|string|max:50',

            // mother
            'm_lname' => 'nullable|string|max:255',
            'm_fname' => 'nullable|string|max:255',
            'm_mname' => 'nullable|string|max:255',
            'm_ext'   => 'nullable|string|max:50',

            // children
            'children' => 'nullable|array',
            'children.*' => 'nullable|string|max:255',
        ]);

        FamilyBackground::create($data);

        return response()->json(['success' => true, 'message' => 'Family background saved successfully!']);
    }

    /**
     * Edit form for an existing record.
     */
    public function edit($id)
    {
        $familyBackground = FamilyBackground::findOrFail($id);
        return view('content.planning.profile.family-background.edit', compact('familyBackground'));
    }

    /**
     * Update an existing record.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            's_lname' => 'nullable|string|max:255',
            's_fname' => 'nullable|string|max:255',
            's_mname' => 'nullable|string|max:255',
            's_ext'   => 'nullable|string|max:50',
            's_occ'   => 'nullable|string|max:255',
            's_emp'   => 'nullable|string|max:255',
            's_addr'  => 'nullable|string|max:500',

            'f_lname' => 'nullable|string|max:255',
            'f_fname' => 'nullable|string|max:255',
            'f_mname' => 'nullable|string|max:255',
            'f_ext'   => 'nullable|string|max:50',

            'm_lname' => 'nullable|string|max:255',
            'm_fname' => 'nullable|string|max:255',
            'm_mname' => 'nullable|string|max:255',
            'm_ext'   => 'nullable|string|max:50',

            'children' => 'nullable|array',
            'children.*' => 'nullable|string|max:255',
        ]);

        $familyBackground = FamilyBackground::findOrFail($id);
        $familyBackground->update($data);

        return response()->json(['success' => true, 'message' => 'Family background updated successfully!']);
    }

    /**
     * Delete a record.
     */
    public function destroy($id)
    {
        $familyBackground = FamilyBackground::findOrFail($id);
        $familyBackground->delete();

        return response()->json(['success' => true, 'message' => 'Family background deleted successfully!']);
    }
}
