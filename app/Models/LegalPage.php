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
     * Uses HTMLPurifier for XSS prevention - industry standard
     * Whitelist-based approach is more secure than regex-based
     */
    public function getSafeContent(): string
    {
        $content = $this->content;

        // Remove XML declarations and processing instructions (both <? and <?xml variants)
        $content = preg_replace('/<\?xml[^>]*>/i', '', $content);
        $content = preg_replace('/<\?[^>]*>/i', '', $content);

        $config = [
            'HTML.Allowed' => 'p,br,strong,em,b,i,u,h1,h2,h3,h4,h5,h6,ul,ol,li,blockquote,pre,code,img[src|alt],a[href|title],table,thead,tbody,tr,th,td,div,span,hr,figure,figcaption',
            'URI.AllowedSchemes' => ['http' => true, 'https' => true, 'mailto' => true],
            'URI.SafeIframeRegexp' => '%^(?:https?:)?//(?:www\.)?(?:youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/|dailymotion\.com/embed/video/)%',
        ];

        $cleaned = clean($content, $config);

        // Remove any remaining HTML-encoded XML declarations that may have been escaped
        $cleaned = preg_replace('/&lt;\?xml[^>]*&gt;/i', '', $cleaned);
        $cleaned = preg_replace('/&lt;\?[^>]*&gt;/i', '', $cleaned);

        return $cleaned;
    }
}
