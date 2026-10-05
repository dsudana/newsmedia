<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Performance Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for application performance optimization
    |
    */

    'cache' => [
        'categories' => [
            'ttl' => 604800, // 1 week in seconds
            'enabled' => true,
        ],
        'articles' => [
            'popular_ttl' => 86400, // 24 hours
            'recent_ttl' => 3600,   // 1 hour
            'enabled' => true,
        ],
        'pages' => [
            'ttl' => 3600, // 1 hour
            'enabled' => true,
        ],
    ],

    'images' => [
        'lazy_loading' => [
            'enabled' => true,
            'attribute' => 'loading="lazy"',
        ],
        'responsive' => [
            'enabled' => true,
            'srcset' => true,
        ],
        'optimization' => [
            'compress' => true,
            'webp' => true,
        ],
    ],

    'database' => [
        'query_optimization' => [
            'eager_load' => true,
            'select_only_needed' => true,
        ],
    ],

    'assets' => [
        'minify_css' => true,
        'minify_js' => true,
        'bundle_size_limit' => '1MB',
    ],

    'headers' => [
        'cache_control' => [
            'static' => 'public, max-age=31536000, immutable',      // 1 year
            'html' => 'public, max-age=3600, s-maxage=3600',        // 1 hour
            'articles' => 'public, max-age=86400, s-maxage=86400',  // 24 hours
        ],
        'compression' => [
            'gzip' => true,
            'brotli' => true,
        ],
    ],

    'monitoring' => [
        'enable_timing' => env('APP_DEBUG', false),
        'log_slow_queries' => env('DB_LOG_QUERIES', false),
        'slow_query_threshold' => 100, // milliseconds
    ],
];
