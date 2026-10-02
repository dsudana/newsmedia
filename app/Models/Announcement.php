<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'category',
        'priority',
        'starts_at',
        'ends_at',
        'is_pinned',
        'views',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_pinned' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: only active announcements within date range
     */
    public function scopeActive($query)
    {
        $now = now();
        return $query->where('is_active', true)
            ->where('starts_at', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>', $now);
            });
    }

    /**
     * Scope: pinned announcements first
     */
    public function scopePinned($query)
    {
        return $query->orderByDesc('is_pinned');
    }

    /**
     * Scope: ordered by priority then date
     */
    public function scopeOrdered($query)
    {
        return $query->orderByDesc('priority')
            ->orderByDesc('is_pinned')
            ->latest('starts_at');
    }

    /**
     * Check if announcement is currently active (in date range)
     */
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        if ($this->starts_at > $now) {
            return false;
        }

        if ($this->ends_at && $this->ends_at <= $now) {
            return false;
        }

        return true;
    }

    /**
     * Get priority badge color
     */
    public function getPriorityColor(): string
    {
        return match ($this->priority) {
            3 => 'bg-red-100 text-red-800',
            2 => 'bg-yellow-100 text-yellow-800',
            default => 'bg-blue-100 text-blue-800',
        };
    }

    /**
     * Get priority label
     */
    public function getPriorityLabel(): string
    {
        return match ($this->priority) {
            3 => 'Urgent',
            2 => 'Medium',
            default => 'Low',
        };
    }

    /**
     * Increment view counter
     */
    public function recordView(): void
    {
        $this->increment('views');
    }
}
