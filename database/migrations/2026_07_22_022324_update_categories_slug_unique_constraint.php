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
        Schema::table('categories', function (Blueprint $table) {
            // Drop the old unique index if exists
            $table->dropUnique('categories_slug_unique');

            // Add composite unique index: allow same slug under different parents
            $table->unique(['parent_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Drop the composite unique index
            $table->dropUnique(['parent_id', 'slug']);

            // Restore the old unique index
            $table->unique('slug');
        });
    }
};
