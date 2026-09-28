<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('focus_keyword')->nullable();
            $table->json('schema_json')->nullable();
            $table->json('internal_links')->nullable();
            $table->json('keywords_used')->nullable();
            $table->timestamps();

            $table->index('article_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_meta');
    }
};
