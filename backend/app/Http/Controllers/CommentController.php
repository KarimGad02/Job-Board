<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Get active comments for a job
    public function index($jobId)
    {
        $comments = Comment::where('job_id', $jobId)
            ->where('status', 'active')
            ->with('user')
            ->latest()
            ->get();
            
        return response()->json($comments);
    }

    // Store a comment on a job
    public function store(Request $request, $jobId)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $job = Job::findOrFail($jobId);

        $comment = Comment::create([
            'job_id' => $job->id,
            'user_id' => Auth::id(),
            'text' => $request->text,
            'status' => 'active',
        ]);

        return response()->json([
            'message' => 'Comment posted successfully.',
            'comment' => $comment->load('user')
        ], 201);
    }
}
