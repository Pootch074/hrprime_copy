<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VoluntaryWork;
use Illuminate\Support\Facades\Auth;

class VoluntaryWorkController extends Controller
{
    /**
     * Display voluntary works of logged-in user
     */
    public function index()
    {
        $voluntaryWorks = VoluntaryWork::where('user_id', Auth::id())->get();

        return view('content.profile.voluntary-work', compact('voluntaryWorks'));
    }

    /**
     * Validation rules
     */
    private function rules()
    {
        return [
            'organization_name'        => 'required|string|max:255',
            'date_from'                => 'required|date',
            'date_to'                  => 'nullable|date|after_or_equal:date_from',
            'number_of_hours'          => 'nullable|integer|min:1|max:100000',
            'position_nature_of_work'  => 'nullable|string|max:500',
        ];
    }

    /**
     * Store voluntary work
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $validated['user_id'] = Auth::id();

        VoluntaryWork::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Voluntary work added successfully.'
        ]);
    }

    /**
     * Update voluntary work
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate($this->rules());

        $work = VoluntaryWork::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $work->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Voluntary work updated successfully.'
        ]);
    }

    /**
     * Delete voluntary work
     */
    public function destroy($id)
    {
        $work = VoluntaryWork::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $work->delete();

        return response()->json([
            'success' => true,
            'message' => 'Voluntary work deleted successfully.'
        ]);
    }
}
