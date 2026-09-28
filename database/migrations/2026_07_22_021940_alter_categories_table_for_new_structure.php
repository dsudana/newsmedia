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
            // Add color if not exists
            if (!Schema::hasColumn('categories', 'color')) {
                $table->string('color')->nullable()->after('icon');
            }

            // Add sort_order if not exists
            if (!Schema::hasColumn('categories', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('color');
            }

            // Add show_in_menu if not exists
            if (!Schema::hasColumn('categories', 'show_in_menu')) {
                $table->boolean('show_in_menu')->default(true)->after('sort_order');
            }

            // Add is_featured if not exists
            if (!Schema::hasColumn('categories', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('show_in_menu');
            }

            // Add indexes
            if (!Schema::hasIndex('categories', 'categories_parent_id_index')) {
                $table->index('parent_id');
            }

            if (!Schema::hasIndex('categories', 'categories_sort_order_index')) {
                $table->index('sort_order');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndexIfExists('categories_parent_id_index');
            $table->dropIndexIfExists('categories_sort_order_index');
            $table->dropColumn(['color', 'sort_order', 'show_in_menu', 'is_featured']);
        });
    }
};
