<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keywords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('keyword')->unique();
            $table->text('description')->nullable();
            $table->enum('intent', ['informational', 'navigational', 'transactional', 'commercial'])->default('informational');
            $table->string('focus_tone')->default('professional');
            $table->integer('target_words')->default(2000);
            $table->enum('status', ['pending', 'processing', 'done', 'failed'])->default('pending');
            $table->string('error_msg')->nullable();
            $table->text('outline')->nullable();
            $table->boolean('use_humanizer')->default(false);
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keywords');
    }
};
