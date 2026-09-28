<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Articles table indexes for common queries
        Schema::table('articles', function (Blueprint $table) {
            $table->index('published_at', 'idx_articles_published');
            $table->index('category_id', 'idx_articles_category');
            $table->index('user_id', 'idx_articles_user');
            $table->index('slug', 'idx_articles_slug');
            $table->fullText(['title', 'excerpt'], 'ft_articles_search');
        });

        // Categories table
        Schema::table('categories', function (Blueprint $table) {
            $table->index('slug', 'idx_categories_slug');
        });

        // Tags table
        Schema::table('tags', function (Blueprint $table) {
            $table->index('slug', 'idx_tags_slug');
        });

        // Homepage sections for quick loading
        Schema::table('homepage_sections', function (Blueprint $table) {
            $table->index('page_type', 'idx_sections_page');
            $table->index('status', 'idx_sections_status');
            $table->index('order', 'idx_sections_order');
        });

        // Newsletter subscribers
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->index('email', 'idx_newsletter_email');
            $table->index('is_active', 'idx_newsletter_active');
            $table->index('subscribed_at', 'idx_newsletter_subscribed');
        });

        // Newsletter sends tracking
        Schema::table('newsletter_sends', function (Blueprint $table) {
            $table->index('template_id', 'idx_sends_template');
            $table->index('status', 'idx_sends_status');
            $table->index('sent_at', 'idx_sends_sent');
        });

        // Ad slots
        Schema::table('ad_slots', function (Blueprint $table) {
            $table->index('slug', 'idx_ads_slug');
            $table->index('is_active', 'idx_ads_active');
            $table->index('location', 'idx_ads_location');
        });

        // Site settings key lookup
        Schema::table('site_settings', function (Blueprint $table) {
            $table->index('key', 'idx_settings_key');
        });

        // Legal pages
        Schema::table('legal_pages', function (Blueprint $table) {
            $table->index('slug', 'idx_legal_slug');
        });

        // Sessions for faster lookups
        Schema::table('sessions', function (Blueprint $table) {
            $table->index('last_activity', 'idx_sessions_activity');
            $table->index('user_id', 'idx_sessions_user');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex('idx_articles_published');
            $table->dropIndex('idx_articles_category');
            $table->dropIndex('idx_articles_user');
            $table->dropIndex('idx_articles_slug');
            $table->dropFullText('ft_articles_search');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('idx_categories_slug');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropIndex('idx_tags_slug');
        });

        Schema::table('homepage_sections', function (Blueprint $table) {
            $table->dropIndex('idx_sections_page');
            $table->dropIndex('idx_sections_status');
            $table->dropIndex('idx_sections_order');
        });

        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->dropIndex('idx_newsletter_email');
            $table->dropIndex('idx_newsletter_active');
            $table->dropIndex('idx_newsletter_subscribed');
        });

        Schema::table('newsletter_sends', function (Blueprint $table) {
            $table->dropIndex('idx_sends_template');
            $table->dropIndex('idx_sends_status');
            $table->dropIndex('idx_sends_sent');
        });

        Schema::table('ad_slots', function (Blueprint $table) {
            $table->dropIndex('idx_ads_slug');
            $table->dropIndex('idx_ads_active');
            $table->dropIndex('idx_ads_location');
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropIndex('idx_settings_key');
        });

        Schema::table('legal_pages', function (Blueprint $table) {
            $table->dropIndex('idx_legal_slug');
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndex('idx_sessions_activity');
            $table->dropIndex('idx_sessions_user');
        });
    }
};
