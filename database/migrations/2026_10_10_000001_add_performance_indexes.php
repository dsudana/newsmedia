<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // For published articles query (most common)
            $table->index(['status', 'published_at']);
            // For category filtering
            $table->index(['category_id', 'status']);
            // For popular posts sorting
            $table->index('views_count');
            // For slug lookups
            $table->index('slug');
        });

        Schema::table('article_views', function (Blueprint $table) {
            // For article view tracking
            $table->index('article_id');
            // For date-based analytics
            $table->index('viewed_at');
            // For composite queries
            $table->index(['article_id', 'viewed_at']);
        });

        Schema::table('categories', function (Blueprint $table) {
            // For active categories with article count
            $table->index('is_active');
        });

        Schema::table('comments', function (Blueprint $table) {
            // For published comments query
            $table->index(['article_id', 'is_approved']);
            // For user comments
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex(['status', 'published_at']);
            $table->dropIndex(['category_id', 'status']);
            $table->dropIndex('views_count');
            $table->dropIndex('slug');
        });

        Schema::table('article_views', function (Blueprint $table) {
            $table->dropIndex('article_id');
            $table->dropIndex('viewed_at');
            $table->dropIndex(['article_id', 'viewed_at']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('is_active');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['article_id', 'is_approved']);
            $table->dropIndex('user_id');
        });
    }
};
