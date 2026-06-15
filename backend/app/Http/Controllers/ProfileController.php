<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json($request->user()->load('roles'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'avatar' => 'sometimes|string'
        ]);

        $user = $request->user();
        $user->update($data);

        return response()->json($user);
    }
}
