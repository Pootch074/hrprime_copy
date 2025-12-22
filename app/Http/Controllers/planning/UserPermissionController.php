<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Module;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Auth;

class UserPermissionController extends Controller
{
    // Show all users and modules
    public function index()
    {
        $authUser = Auth::user();
        $users = User::whereHas('section', function($query) {
            $query->where('abbreviation', 'HR-PLANNING');
        })->get();
        $modules = Module::all();
        $actions = ['view', 'create', 'edit'];

        // Only users in HR-PLANNING section can assign all permissions
        $assignablePermissions = $authUser->section === 'HR-PLANNING' 
            ? Permission::pluck('name')->toArray() 
            : [];

        return view('content.planning.user-permission', compact(
            'users', 'modules', 'actions', 'assignablePermissions'
        ));
    }

    // Return permissions for a specific user
    public function getUserPermissions($user_id)
    {
        $user = User::findOrFail($user_id);
        return response()->json($user->getPermissionNames());
    }

    // Sync all permissions at once (HR-PLANNING section only)
    public function update(Request $request)
    {
        $authUser = Auth::user();

        // Only HR-PLANNING section users can assign permissions
        if ($authUser->section !== 'HR-PLANNING') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $user = User::findOrFail($request->user_id);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $user->syncPermissions($request->permissions ?? []);

        return response()->json(['success' => 'Permissions updated successfully']);
    }
}
