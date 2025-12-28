<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class BasicInformationController extends Controller
{
    // Basic Information
    public function index()
    {
        $employee = User::with([
            'permRegion', 'permProvince', 'permCity', 'permBarangay',
            'resRegion', 'resProvince', 'resCity', 'resBarangay'
        ])->findOrFail(Auth::id());

        return view('content.profile.basic-information', compact('employee'));
    }

public function update(Request $request)
{
    $employee = auth()->user();

    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username,' . $employee->id,
        'employee_id' => 'required|string|max:50|unique:users,employee_id,' . $employee->id,
        'password' => 'nullable|min:8',
        'citizenship' => 'required|string|in:Filipino,Dual Citizenship',
        'citizenship_type' => 'nullable|string|in:by_birth,by_naturalization',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
    ]);

    // Fill basic info
    $employee->fill($request->only([
        'first_name', 'middle_name', 'last_name', 'extension_name',
        'username', 'employee_id', 'birthday', 'place_of_birth',
        'gender', 'civil_status', 'blood_type', 'height', 'weight',
        'tel_no', 'mobile_no', 'perm_country', 'citizenship'
    ]));

    // Citizenship type
    $employee->citizenship_type = $request->citizenship === 'Dual Citizenship' ? $request->citizenship_type : null;

    // Addresses
    $employee->fill($request->only([
        'perm_region', 'perm_province', 'perm_city', 'perm_barangay',
        'perm_street', 'perm_house_no', 'perm_zipcode',
        'res_region', 'res_province', 'res_city', 'res_barangay',
        'res_street', 'res_house_no', 'res_zipcode'
    ]));

    // Password
    if ($request->filled('password')) {
        $employee->password = Hash::make($request->password);
    }

    // Profile image
    if ($request->hasFile('profile_image')) {
        // Delete old image if exists
        if ($employee->profile_image && Storage::disk('public')->exists($employee->profile_image)) {
            Storage::disk('public')->delete($employee->profile_image);
        }

        // Store new image
        $employee->profile_image = $request->file('profile_image')->store('profile_images', 'public');
    }

    $employee->save();

    return response()->json([
        'success' => true,
        'message' => 'Basic Information updated successfully!',
        'employee' => $employee
    ]);
}
}
