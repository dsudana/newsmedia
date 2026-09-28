<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Change existing status enum to include more options
            $table->dropColumn('status');
            $table->enum('status', ['draft', 'published', 'scheduled', 'archived'])->default('draft')->after('schema_type');

            // Add new columns for scheduling and AI
            $table->timestamp('scheduled_at')->nullable()->after('published_at');
            $table->integer('read_time')->default(5)->after('views_count');
            $table->integer('word_count')->default(0)->after('read_time');
            $table->string('ai_provider')->nullable()->after('word_count');
            $table->integer('seo_score')->default(0)->after('ai_provider');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['scheduled_at', 'read_time', 'word_count', 'ai_provider', 'seo_score']);
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft')->change();
        });
    }
};
