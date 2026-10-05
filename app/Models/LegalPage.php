<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalPage extends Model
{
    protected $fillable = ['slug', 'title', 'content'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get sanitized content safe for display
     * Uses regex-based filtering for XSS prevention
     * Removes all potentially dangerous HTML/JavaScript
     */
    public function getSafeContent(): string
    {
        // Remove all script tags and event handlers
        $content = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi', '', $this->content);
        $content = preg_replace('/on\w+\s*=\s*["\'][^"\']*["\']/gi', '', $content);
        $content = preg_replace('/on\w+\s*=\s*[^\s>]*/gi', '', $content);

        // Remove javascript: URLs from href and src
        $content = preg_replace('/(href|src)\s*=\s*["\']javascript:[^"\']*["\']/gi', '$1=""', $content);

        // Remove data: URLs from href (can be XSS vector)
        $content = preg_replace('/(href)\s*=\s*["\']data:[^"\']*["\']/gi', '$1="javascript:void(0)"', $content);

        // Remove style attributes that might contain malicious CSS
        $content = preg_replace('/style\s*=\s*["\'][^"\']*["\']/gi', '', $content);

        // Remove iframe, object, embed, form tags entirely
        $content = preg_replace('/<(iframe|object|embed|form|input|button)\b[^<]*(?:(?!<\/\1>)<[^<]*)*<\/\1>/gi', '', $content);

        return trim($content);
    }
}
