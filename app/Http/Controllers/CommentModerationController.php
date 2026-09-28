<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentModerationController extends Controller
{
    public function index()
    {
        $pendingComments = Comment::where('is_approved', false)
            ->with(['article', 'user', 'parent'])
            ->latest()
            ->paginate(20);

        $stats = [
            'pending' => Comment::where('is_approved', false)->count(),
            'approved' => Comment::where('is_approved', true)->count(),
            'total' => Comment::count(),
        ];

        return view('admin.comments.index', compact('pendingComments', 'stats'));
    }

    public function approve(Comment $comment)
    {
        $comment->update(['is_approved' => true, 'status' => 'approved']);

        return back()->with('success', 'Comment approved');
    }

    public function reject(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Comment rejected');
    }

    public function approveMultiple(Request $request)
    {
        $ids = $request->input('ids', []);
        Comment::whereIn('id', $ids)->update(['is_approved' => true, 'status' => 'approved']);

        return back()->with('success', count($ids) . ' comments approved');
    }

    public function rejectMultiple(Request $request)
    {
        $ids = $request->input('ids', []);
        Comment::whereIn('id', $ids)->delete();

        return back()->with('success', count($ids) . ' comments rejected');
    }
}
