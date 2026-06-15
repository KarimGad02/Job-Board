<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\AdminRoleController;
// New Controllers for Jobs & Taxonomy
use App\Http\Controllers\JobController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TechnologyController;

// Public Auth Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Public Taxonomy & Job Routes
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/technologies', [TechnologyController::class, 'index']);
Route::get('/jobs/{job}', [JobController::class, 'show']); 

// Protected Routes (Require Login)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('profile', [ProfileController::class, 'show']);
    Route::put('profile', [ProfileController::class, 'update']);

    // Admin-only routes
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->middleware('role:admin');
    Route::get('admin/users', [AdminRoleController::class, 'index'])->middleware('role:admin');
    Route::put('admin/users/{id}/roles', [AdminRoleController::class, 'updateRoles'])->middleware('role:admin');

    // Employer-only routes
    Route::get('employer/dashboard', [EmployerController::class, 'dashboard'])->middleware('role:employer');

    // Employer Job Management Routes (Nested to require both login and employer role)
    Route::middleware('role:employer')->group(function () {
        Route::get('/employer/jobs', [JobController::class, 'myJobs']); 
        Route::post('/jobs', [JobController::class, 'store']); 
        Route::put('/jobs/{job}', [JobController::class, 'update']); 
        Route::delete('/jobs/{job}', [JobController::class, 'destroy']);
    });
});