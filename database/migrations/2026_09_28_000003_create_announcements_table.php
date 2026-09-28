<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('title');
            $table->text('content');
            $table->string('category')->nullable()->comment('e.g., urgent, info, warning');
            $table->tinyInteger('priority')->default(1)->comment('1=low, 2=medium, 3=high');

            // Date range for display
            $table->dateTime('starts_at')->comment('When announcement becomes visible');
            $table->dateTime('ends_at')->nullable()->comment('When announcement disappears (null = never)');

            // Features
            $table->boolean('is_pinned')->default(false)->comment('Pin to top');
            $table->unsignedBigInteger('views')->default(0);

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('starts_at');
            $table->index('ends_at');
            $table->index('is_pinned');
            $table->index('is_active');
            $table->index(['is_active', 'starts_at', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
