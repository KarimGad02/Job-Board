<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function store(Request $request, $jobId)
    {
        $request->validate([
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
        ]);

        $job = Job::findOrFail($jobId);

        // Check if already applied
        $existing = Application::where('job_id', $jobId)
                               ->where('candidate_id', $request->user()->id)
                               ->first();
        if ($existing) {
            return response()->json(['message' => 'You have already applied for this job.'], 400);
        }

        $path = $request->file('resume')->store('resumes', 'public');

        $application = Application::create([
            'job_id' => $jobId,
            'candidate_id' => $request->user()->id,
            'resume_path' => $path,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 'pending'
        ]);

        return response()->json(['message' => 'Application submitted successfully.', 'application' => $application], 201);
    }

    public function candidateApplications(Request $request)
    {
        $applications = Application::with('job.employer')
            ->where('candidate_id', $request->user()->id)
            ->latest()
            ->get();
        return response()->json($applications);
    }

    public function employerApplications(Request $request, $jobId)
    {
        $job = Job::findOrFail($jobId);
        if ($job->employer_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $applications = Application::with('candidate')
            ->where('job_id', $jobId)
            ->latest()
            ->get();
        return response()->json($applications);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected,pending'
        ]);

        $application = Application::with('job')->findOrFail($id);

        if ($application->job->employer_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $application->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated successfully.', 'application' => $application]);
    }

    public function destroy(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        if ($application->candidate_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete resume file if exists
        if ($application->resume_path) {
            Storage::disk('public')->delete($application->resume_path);
        }

        $application->delete();

        return response()->json(['message' => 'Application canceled successfully.']);
    }
}
