<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSend extends Model
{
    protected $fillable = ['template_id', 'total_subscribers', 'sent_count', 'failed_count', 'status', 'scheduled_at', 'sent_at'];
    protected $casts = ['scheduled_at' => 'datetime', 'sent_at' => 'datetime'];

    public function template()
    {
        return $this->belongsTo(NewsletterTemplate::class, 'template_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
