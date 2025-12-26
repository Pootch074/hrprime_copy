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

        // Example: restrict assignable permissions if needed
        $assignablePermissions = Permission::pluck('name')->toArray();

        return view('content.planning.user-permission', compact('users', 'modules', 'actions', 'assignablePermissions'));
    }

    public function getUserPermissions($user_id)
    {
        $user = User::findOrFail($user_id);
        return response()->json($user->getPermissionNames()->toArray());
    }

    public function update(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permissions' => 'array',
        ]);

        $user = User::findOrFail($request->user_id);

        // Sync all permissions at once
        $permissions = $request->permissions ?? [];
        $user->syncPermissions($permissions);

        return response()->json(['success' => 'Permissions updated successfully']);
    }
}
