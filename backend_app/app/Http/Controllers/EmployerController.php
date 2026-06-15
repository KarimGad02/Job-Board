<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployerController extends Controller
{
    public function dashboard(Request $request)
    {
        return response()->json(['message' => 'Employer dashboard', 'user' => $request->user()]);
    }
}
