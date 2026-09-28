<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['article', 'user'])->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('article_id')) {
            $query->where('article_id', $request->article_id);
        }

        $comments = $query->paginate(20);
        $statusCounts = [
            'all' => Comment::count(),
            'pending' => Comment::where('status', 'pending')->count(),
            'approved' => Comment::where('status', 'approved')->count(),
            'rejected' => Comment::where('status', 'rejected')->count(),
        ];

        return view('admin.comments.index', compact('comments', 'statusCounts'));
    }

    public function show(Comment $comment)
    {
        return view('admin.comments.show', compact('comment'));
    }

    public function create()
    {
        // Comments are created via public form, not admin panel
        return abort(404);
    }

    public function store(Request $request)
    {
        // Comments are submitted via public form
        return abort(404);
    }

    public function edit(Comment $comment)
    {
        // Comments are moderated, not edited
        return abort(404);
    }

    public function update(Request $request, Comment $comment)
    {
        // Comments are moderated, not edited
        return abort(404);
    }

    public function approve(Comment $comment)
    {
        $comment->update(['status' => 'approved']);
        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment approved successfully.');
    }

    public function reject(Comment $comment)
    {
        $comment->update(['status' => 'rejected']);
        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment rejected successfully.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment deleted successfully.');
    }

    public function bulkApprove(Request $request)
    {
        Comment::whereIn('id', $request->ids)->update(['status' => 'approved']);
        return response()->json(['success' => true, 'message' => 'Comments approved']);
    }

    public function bulkReject(Request $request)
    {
        Comment::whereIn('id', $request->ids)->update(['status' => 'rejected']);
        return response()->json(['success' => true, 'message' => 'Comments rejected']);
    }

    public function bulkDelete(Request $request)
    {
        Comment::whereIn('id', $request->ids)->delete();
        return response()->json(['success' => true, 'message' => 'Comments deleted']);
    }
}
