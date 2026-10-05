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
     * Allows safe HTML tags while preventing XSS attacks
     */
    public function getSafeContent(): string
    {
        $allowed_tags = [
            'p', 'br', 'strong', 'em', 'b', 'i', 'u', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'ul', 'ol', 'li', 'blockquote', 'pre', 'code', 'img', 'a', 'table', 'thead',
            'tbody', 'tr', 'th', 'td', 'div', 'span', 'hr', 'figure', 'figcaption'
        ];

        return strip_tags($this->content, '<' . implode('><', $allowed_tags) . '>');
    }
}
