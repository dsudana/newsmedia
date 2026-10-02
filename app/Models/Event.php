<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'event_date',
        'event_end_date',
        'location',
        'location_details',
        'featured_image',
        'event_url',
        'category',
        'status',
        'capacity',
        'registered',
        'is_featured',
        'views',
        'is_active',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'event_end_date' => 'datetime',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = self::uniqueSlug($event->title);
            }
        });

        static::updating(function ($event) {
            if ($event->isDirty('title') && !$event->isDirty('slug')) {
                $event->slug = self::uniqueSlug($event->title, $event->id);
            }
        });
    }

    private static function uniqueSlug($title, $exceptId = null)
    {
        $slug = Str::slug($title);
        $count = self::where('slug', 'like', $slug . '%')
            ->when($exceptId, fn($q) => $q->where('id', '!=', $exceptId))
            ->count();

        return $count ? "{$slug}-" . Str::random(6) : $slug;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now())
            ->where('is_active', true)
            ->orderBy('event_date');
    }

    /**
     * Scope: past events
     */
    public function scopePast($query)
    {
        return $query->where('event_date', '<', now())
            ->where('is_active', true)
            ->orderByDesc('event_date');
    }

    /**
     * Scope: active and published
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: featured events
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Check if event is upcoming
     */
    public function isUpcoming(): bool
    {
        return $this->event_date >= now();
    }

    /**
     * Check if event is ongoing
     */
    public function isOngoing(): bool
    {
        $now = now();
        return $this->event_date <= $now && (!$this->event_end_date || $this->event_end_date >= $now);
    }

    /**
     * Check if event is past
     */
    public function isPast(): bool
    {
        $end = $this->event_end_date ?? $this->event_date;
        return $end < now();
    }

    /**
     * Get formatted event date
     */
    public function getFormattedDateAttribute(): string
    {
        if ($this->event_end_date && $this->event_end_date->format('Y-m-d') !== $this->event_date->format('Y-m-d')) {
            return $this->event_date->format('M d, Y') . ' - ' . $this->event_end_date->format('M d, Y');
        }

        return $this->event_date->format('M d, Y');
    }

    /**
     * Get event time
     */
    public function getTimeAttribute(): string
    {
        return $this->event_date->format('H:i');
    }

    /**
     * Get available spots
     */
    public function getAvailableSpotsAttribute(): ?int
    {
        if (!$this->capacity) {
            return null;
        }

        return max(0, $this->capacity - $this->registered);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'scheduled' => 'bg-blue-100 text-blue-800',
            'ongoing' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'completed' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
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
