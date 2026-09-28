<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Article;
use Illuminate\Http\Request;

class PublicCommentController extends Controller
{
    /**
     * Store a new comment or reply
     */
    public function store(Request $request, Article $article)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'content' => 'required|string|min:5|max:2000',
            'parent_id' => 'nullable|integer|exists:comments,id',
            'honeypot' => 'nullable|string|max:0',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'content.required' => 'Komentar harus diisi',
            'content.min' => 'Komentar minimal 5 karakter',
            'content.max' => 'Komentar maksimal 2000 karakter',
            'parent_id.exists' => 'Komentar induk tidak ditemukan',
        ]);

        // Spam check
        if (!empty($validated['honeypot'])) {
            return back()->with('success', 'Komentar Anda berhasil dikirim');
        }

        $parentId = $validated['parent_id'] ?? null;
        $isReply = false;

        // Validate parent comment if replying
        if ($parentId) {
            $parentComment = Comment::find($parentId);

            // Parent must belong to same article
            if ($parentComment->article_id !== $article->id) {
                return back()->withErrors(['content' => 'Komentar induk tidak valid']);
            }

            // Parent must be approved
            if (!$parentComment->is_approved) {
                return back()->withErrors(['content' => 'Komentar induk belum disetujui']);
            }

            // Cannot reply to replies (max 2 levels)
            if ($parentComment->isReply()) {
                return back()->withErrors(['content' => 'Hanya bisa membalas komentar utama']);
            }

            $isReply = true;
        }

        // Auto-approve if user is admin/verified, otherwise pending
        $isApproved = auth()->check() && auth()->user()->hasRole('admin');

        $comment = $article->comments()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'content' => $validated['content'],
            'user_id' => auth()->id(),
            'parent_id' => $parentId,
            'status' => $isApproved ? 'approved' : 'pending',
            'is_approved' => $isApproved,
        ]);

        if ($isReply) {
            return back()->with('success', 'Balasan Anda akan ditampilkan setelah disetujui oleh admin');
        }

        return back()->with('success', 'Komentar Anda akan ditampilkan setelah disetujui oleh admin');
    }

    /**
     * Delete a comment (only owner or admin)
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus');
    }
}
