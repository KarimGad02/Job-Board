<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\AdminRoleController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('profile', [ProfileController::class, 'show']);
    Route::put('profile', [ProfileController::class, 'update']);

    // Admin-only route
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->middleware('role:admin');

    // Employer-only route
    Route::get('employer/dashboard', [EmployerController::class, 'dashboard'])->middleware('role:employer');

    // Admin role management
    Route::get('admin/users', [AdminRoleController::class, 'index'])->middleware('role:admin');
    Route::put('admin/users/{id}/roles', [AdminRoleController::class, 'updateRoles'])->middleware('role:admin');
});
