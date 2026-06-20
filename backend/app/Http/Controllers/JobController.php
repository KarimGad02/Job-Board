<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    // Public: Get all open jobs (with search, filters, sorting, and pagination)
    public function index(Request $request)
    {
        $query = Job::where('status', 'open')
            ->with(['category', 'employer']);

        // 1. Search by Keyword (title/description)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 2. Filter by Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // 3. Filter by Work Type (remote, onsite, hybrid)
        if ($request->filled('work_type')) {
            $query->where('work_type', $request->input('work_type'));
        }

        // 4. Filter by Location
        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->input('location')}%");
        }

        // 5. Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        // 6. Pagination
        $perPage = $request->input('per_page', 9);
        $jobs = $query->paginate($perPage);

        return response()->json($jobs);
    }

    // Employer Dashboard APIs (Get jobs for logged-in employer)
    public function myJobs()
    {
        $jobs = Job::where('employer_id', Auth::id())
                    ->with(['category'])
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
        'skills_and_qualifications' => 'nullable|string',
        'salary_range' => 'nullable|string|max:255',
        'benefits' => 'nullable|string',
        'location' => 'nullable|string|max:255',
        'work_type' => 'required|in:remote,onsite,hybrid',
        'application_deadline' => 'nullable|date',
        'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'status' => 'required|in:open,closed,draft',
    ]);

    // 1. Manually add the IDs required by your database schema
    $validated['employer_id'] = Auth::id();
    $validated['user_id'] = Auth::id();

    // Handle company logo upload
    if ($request->hasFile('company_logo')) {
        $path = $request->file('company_logo')->store('logos', 'public');
        $validated['company_logo'] = $path;
    }

    // 2. Create the job directly from the model
    $job = \App\Models\Job::create($validated);

    return response()->json([
        'message' => 'Job created successfully', 
        'job' => $job->load(['category'])
    ], 201);
}
    // Job Details Endpoint (Employer View & Candidate View)
    public function show(Job $job)
    {
        return response()->json($job->load(['category', 'employer']));
    }

    // Update Job
    public function update(Request $request, Job $job)
    {
        // Ensure the user owns this job before updating
        if ($job->employer_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'responsibilities' => 'nullable|string',
            'skills_and_qualifications' => 'nullable|string',
            'salary_range' => 'nullable|string|max:255',
            'benefits' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'work_type' => 'sometimes|in:remote,onsite,hybrid',
            'application_deadline' => 'nullable|date',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|in:open,closed,draft',
        ]);

        if ($request->hasFile('company_logo')) {
            $path = $request->file('company_logo')->store('logos', 'public');
            $validated['company_logo'] = $path;
        }

        $job->update($validated);

        return response()->json([
            'message' => 'Job updated successfully', 
            'job' => $job->load(['category'])
        ]);
    }

    // Delete Job
    public function destroy(Job $job)
    {
        if ($job->employer_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $job->delete();
        return response()->json(['message' => 'Job deleted successfully']);
    }
}