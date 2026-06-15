<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class AdminRoleController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return response()->json($users);
    }

    public function updateRoles(Request $request, $id)
    {
        $data = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'string|exists:roles,name'
        ]);

        $user = User::findOrFail($id);

        // find role ids for given names
        $roleIds = Role::whereIn('name', $data['roles'])->pluck('id')->toArray();
        $user->roles()->sync($roleIds);

        return response()->json($user->load('roles'));
    }
}
