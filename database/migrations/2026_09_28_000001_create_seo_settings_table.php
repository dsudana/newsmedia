<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_name')->unique()->comment('Unique identifier for the page (e.g., home, blog)');
            $table->string('page_title')->comment('Display title for the page');

            // Meta Tags
            $table->string('meta_title', 60)->nullable()->comment('Meta title tag (60 chars max)');
            $table->string('meta_description', 160)->nullable()->comment('Meta description (160 chars max)');
            $table->text('meta_keywords')->nullable()->comment('Meta keywords (comma separated)');
            $table->string('canonical_url')->nullable()->comment('Canonical URL for the page');

            // Open Graph (Social Media)
            $table->string('og_title')->nullable()->comment('OpenGraph title');
            $table->text('og_description')->nullable()->comment('OpenGraph description');
            $table->string('og_image')->nullable()->comment('OpenGraph image URL');
            $table->string('og_type')->nullable()->default('website')->comment('OpenGraph type (website, article, etc)');

            // Twitter Card
            $table->string('twitter_title')->nullable()->comment('Twitter card title');
            $table->text('twitter_description')->nullable()->comment('Twitter card description');
            $table->string('twitter_image')->nullable()->comment('Twitter card image URL');
            $table->string('twitter_card')->nullable()->default('summary_large_image')->comment('Twitter card type');

            // Structured Data (Schema.org)
            $table->json('structured_data')->nullable()->comment('JSON-LD structured data');

            // Robots & Sitemap
            $table->boolean('index')->default(true)->comment('Allow indexing (robots index)');
            $table->boolean('follow')->default(true)->comment('Allow following links (robots follow)');
            $table->decimal('sitemap_priority', 2, 1)->default(0.8)->comment('Sitemap priority (0.0-1.0)');
            $table->string('sitemap_changefreq')->default('weekly')->comment('Sitemap change frequency');

            // Status
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->index('page_name');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
