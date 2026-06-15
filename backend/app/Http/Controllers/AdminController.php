<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        return response()->json(['message' => 'Admin dashboard', 'user' => $request->user()]);
    }
}
