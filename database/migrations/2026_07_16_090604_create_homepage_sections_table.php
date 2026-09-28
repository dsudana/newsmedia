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
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->enum('page_type', ['homepage', 'blog_index'])->default('homepage');
            $table->string('section_type', 50);
            $table->string('title', 255)->nullable();
            $table->text('subtitle')->nullable();
            $table->json('config')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('status')->default(1);
            $table->json('settings')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('page_type');
            $table->index('section_type');
            $table->index('order');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_sections');
    }
};
