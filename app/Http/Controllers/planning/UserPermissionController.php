<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Module;
use Spatie\Permission\Models\Permission;

class UserPermissionController extends Controller
{
    public function index()
    {
        $users = User::all();
        $modules = Module::all();
        $actions = ['view', 'create', 'edit'];

        return view('content.planning.user-permission', compact('users', 'modules', 'actions'));
    }

    // Get permissions of a specific user
    public function getUserPermissions($user_id)
    {
        $user = User::findOrFail($user_id);
        return response()->json($user->getPermissionNames()->toArray());
    }

    // Update permissions
    public function update(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $loggedInUser = auth()->user();

        // Only allow users with 'edit user-permissions' to update
        if (!$loggedInUser->can('edit user-permissions')) {
            return response()->json([
                'error' => 'You are not allowed to update permissions.'
            ], 403);
        }

        $user = User::findOrFail($request->user_id);
        $permissions = $request->permissions ?? [];

        // Auto-create missing permissions
        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'web'
            ]);
        }

        // Assign permissions
        $user->syncPermissions($permissions);

        return response()->json([
            'success' => 'Permissions updated successfully!',
            'synced_permissions' => $permissions,
        ]);
    }
}
