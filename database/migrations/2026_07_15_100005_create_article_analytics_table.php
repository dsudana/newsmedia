<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->date('date');
            $table->integer('views')->default(0);
            $table->integer('unique_visitors')->default(0);
            $table->integer('scroll_depth')->default(0); // percentage
            $table->integer('avg_time_on_page')->default(0); // seconds
            $table->timestamps();

            $table->unique(['article_id', 'date']);
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_analytics');
    }
};
