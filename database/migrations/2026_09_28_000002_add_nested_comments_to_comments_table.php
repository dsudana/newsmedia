<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Add parent_id for nested comments (max 2 levels)
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');

            // Add is_approved for comment moderation
            $table->boolean('is_approved')->default(false)->after('status');

            // Add indices for better query performance
            $table->index('parent_id');
            $table->index('is_approved');
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'is_approved']);
        });
    }
};
