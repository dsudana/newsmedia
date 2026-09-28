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
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'series_name')) {
                $table->string('series_name')->nullable()->after('is_featured');
            }
            if (!Schema::hasColumn('articles', 'series_order')) {
                $table->integer('series_order')->nullable()->after('series_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['series_name', 'series_order']);
        });
    }
};
