<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    // Employer Dashboard APIs (Get jobs for logged-in employer)
    public function myJobs()
    {
        $jobs = Job::where('user_id', Auth::id())
                    ->with(['category', 'technologies'])
                    ->latest()
                    ->get();
                    
        return response()->json($jobs);
    }

    // Store a new job
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'responsibilities' => 'nullable|string',
            'salary_range' => 'nullable|string|max:255',
            'benefits' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'work_type' => 'required|in:remote,onsite,hybrid',
            'application_deadline' => 'nullable|date',
            'company_logo' => 'nullable|string',
            'status' => 'required|in:open,closed,draft',
            'technologies' => 'array',
            'technologies.*' => 'exists:technologies,id'
        ]);

        $job = Auth::user()->jobs()->create($validated);

        if ($request->has('technologies')) {
            $job->technologies()->attach($request->technologies);
        }

        return response()->json([
            'message' => 'Job created successfully', 
            'job' => $job->load(['category', 'technologies'])
        ], 201);
    }

    // Job Details Endpoint (Employer View & Candidate View)
    public function show(Job $job)
    {
        return response()->json($job->load(['category', 'technologies', 'employer']));
    }

    // Update Job
    public function update(Request $request, Job $job)
    {
        // Ensure the user owns this job before updating
        if ($job->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'responsibilities' => 'nullable|string',
            'salary_range' => 'nullable|string|max:255',
            'benefits' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'work_type' => 'sometimes|in:remote,onsite,hybrid',
            'application_deadline' => 'nullable|date',
            'company_logo' => 'nullable|string',
            'status' => 'sometimes|in:open,closed,draft',
            'technologies' => 'array',
            'technologies.*' => 'exists:technologies,id'
        ]);

        $job->update($validated);

        if ($request->has('technologies')) {
            $job->technologies()->sync($request->technologies);
        }

        return response()->json([
            'message' => 'Job updated successfully', 
            'job' => $job->load(['category', 'technologies'])
        ]);
    }

    // Delete Job
    public function destroy(Job $job)
    {
        if ($job->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $job->delete();
        return response()->json(['message' => 'Job deleted successfully']);
    }
}