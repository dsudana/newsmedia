<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Composite index for the most common homepage query
            $table->index(['status', 'published_at'], 'idx_articles_status_published');

            // Index for featured image filtering
            $table->index('featured_image', 'idx_articles_featured_image');

            // Index for category relationship queries
            $table->index(['category_id', 'status'], 'idx_articles_category_status');

            // Index for user relationship queries
            $table->index(['user_id', 'status'], 'idx_articles_user_status');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex('idx_articles_status_published');
            $table->dropIndex('idx_articles_featured_image');
            $table->dropIndex('idx_articles_category_status');
            $table->dropIndex('idx_articles_user_status');
        });
    }
};
