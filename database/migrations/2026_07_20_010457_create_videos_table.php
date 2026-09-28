<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('youtube_url');
            $table->string('youtube_id');
            $table->string('thumbnail_url')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->integer('order')->default(0);
            $table->enum('status', ['published', 'draft'])->default('published');
            $table->integer('views_count')->default(0);
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('published_at')->nullable();
            $table->timestamps();

            $table->index('youtube_id');
            $table->index('status');
            $table->index('category_id');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
