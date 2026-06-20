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
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CommentController;


// Public Auth Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Public Taxonomy & Job Routes
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/technologies', [TechnologyController::class, 'index']);
Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/{job}', [JobController::class, 'show']); 
Route::get('/jobs/{job}/comments', [CommentController::class, 'index']);

// Protected Routes (Require Login)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('profile', [ProfileController::class, 'show']);
    Route::put('profile', [ProfileController::class, 'update']);
    Route::post('/payment/create-intent', [PaymentController::class, 'createPaymentIntent']);
    Route::post('/jobs/{job}/comments', [CommentController::class, 'store']);
    // Admin-only routes
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->middleware('role:admin');
    Route::get('admin/users', [AdminRoleController::class, 'index'])->middleware('role:admin');
    Route::put('admin/users/{id}/roles', [AdminRoleController::class, 'updateRoles'])->middleware('role:admin');
    Route::get('admin/stats', [AdminController::class, 'stats'])->middleware('role:admin');
    Route::get('admin/jobs', [AdminController::class, 'jobs'])->middleware('role:admin');
    Route::put('admin/job/{id}', [AdminController::class, 'updateJob'])->middleware('role:admin');
    Route::get('admin/comments', [AdminController::class, 'comments'])->middleware('role:admin');
    Route::put('admin/comment/{id}', [AdminController::class, 'updateComment'])->middleware('role:admin');



    // Application Routes (Candidate)
    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'store']);
    Route::get('/candidate/applications', [ApplicationController::class, 'candidateApplications']);
    Route::delete('/applications/{application}', [ApplicationController::class, 'destroy']);

    // Employer-only routes
    Route::get('employer/dashboard', [EmployerController::class, 'dashboard'])->middleware('role:employer');

    // Employer Job Management Routes (Nested to require both login and employer role)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/employer/jobs', [JobController::class, 'myJobs']); 
        Route::post('/jobs', [JobController::class, 'store']); 
        Route::put('/jobs/{job}', [JobController::class, 'update']); 
        Route::delete('/jobs/{job}', [JobController::class, 'destroy']);
        
        // Employer Application Management
        Route::get('/employer/jobs/{job}/applications', [ApplicationController::class, 'employerApplications']);
        Route::put('/applications/{application}/status', [ApplicationController::class, 'updateStatus']);
    });
});