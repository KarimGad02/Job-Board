<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Job;
use App\Models\Role;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        return response()->json(['message' => 'Admin dashboard', 'user' => $request->user()]);
    }
   
    public function stats(Request $request)
    {
        $employerRoleId = Role::where('name', 'employer')->value('id');
        $candidateRoleId = Role::where('name', 'candidate')->value('id');

        $totalUsers = User::count();

        $totalEmployers = User::whereHas('roles', function ($query) use ($employerRoleId) {
        $query->where('roles.id', $employerRoleId);
        })->count();

        $totalCandidates = User::whereHas('roles', function ($query) use ($candidateRoleId) {
        $query->where('roles.id', $candidateRoleId);
        })->count();

        $totalJobs = Job::count();

        $pendingJobs = Job::where('status', 'draft')->count();

        $approvedJobs = Job::where('status', 'open')->count();

        $rejectedJobs = Job::where('status', 'closed')->count();


        return response()->json([
        'total_users' => $totalUsers,
        'total_employers' => $totalEmployers,
        'total_candidates' => $totalCandidates,
        'total_jobs' => $totalJobs,
        'pending_jobs' => $pendingJobs,
        'approved_jobs' => $approvedJobs,
        'rejected_jobs' => $rejectedJobs,
        ]);
    }
    public function jobs(Request $request)
    {
        $totalJobs = Job::get();
        return response()->json( $totalJobs);
    }
    public function updateJob(Request $request, $id)
    {
        $request->validate([
        'status' => 'required|in:draft,open,closed',
        ]);

        $job = Job::findOrFail($id);

        $job->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Job status updated successfully.',
            'job' => $job,
        ]);
    }
}