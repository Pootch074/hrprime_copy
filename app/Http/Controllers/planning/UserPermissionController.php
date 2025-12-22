<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class UserPermissionController extends Controller
{
    public function index()
    {
        $users = User::all();
        $modules = \App\Models\Module::all();

        return view('content.planning.user-permission', compact('users', 'modules'));
    }

    public function getUserPermissions($user_id)
    {
        $user = User::findOrFail($user_id);
        return response()->json($user->getPermissionNames());
    }

    public function update(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'permissions' => 'array',
        'permissions.*' => 'string|exists:permissions,name',
    ]);

    $user = User::findOrFail($request->user_id);
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    $user->syncPermissions($request->permissions);

    return response()->json(['success' => 'Permissions updated successfully']);
}


}
