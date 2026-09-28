<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $fillable = [
        'article_id',
        'user_id',
        'parent_id',
        'name',
        'email',
        'content',
        'status',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Parent comment (for replies)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Direct child replies to this comment
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->where('is_approved', true)
            ->oldest();
    }

    /**
     * All nested replies recursively
     */
    public function allReplies(): HasMany
    {
        return $this->replies()->with('allReplies');
    }

    /**
     * Scope: only approved comments
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope: only top-level comments (no parent)
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Check if comment is a reply
     */
    public function isReply(): bool
    {
        return !is_null($this->parent_id);
    }

    /**
     * Get depth level (max 2: top-level=0, reply=1)
     */
    public function getDepthLevel(): int
    {
        if (!$this->isReply()) {
            return 0;
        }

        // Only support 1 level of nesting
        return 1;
    }

    /**
     * Cannot reply to replies (max 2 levels)
     */
    public function canReply(): bool
    {
        return !$this->isReply();
    }

    /**
     * Check if user can delete this comment
     */
    public function canDeleteBy($user): bool
    {
        if (!$user) {
            return false;
        }

        // Admin can delete any comment
        if ($user->hasRole('admin')) {
            return true;
        }

        // User can delete their own comment only if it's top-level
        return $this->user_id === $user->id && !$this->isReply();
    }
}
