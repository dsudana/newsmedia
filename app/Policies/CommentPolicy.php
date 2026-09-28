<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Determine if the user can delete the comment
     */
    public function delete(?User $user, Comment $comment): bool
    {
        // Admin can delete any comment
        if ($user && $user->hasRole('admin')) {
            return true;
        }

        // User can delete their own top-level comments only
        if ($user && $comment->user_id === $user->id && !$comment->isReply()) {
            return true;
        }

        return false;
    }

    /**
     * Determine if user can reply to this comment
     */
    public function reply(?User $user, Comment $comment): bool
    {
        // Only approved top-level comments can have replies
        return $comment->is_approved && !$comment->isReply();
    }
}
