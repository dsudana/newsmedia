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
            $table->boolean('is_breaking')->default(false)->index()->after('status');
            $table->tinyInteger('priority')->default(5)->index()->after('is_breaking'); // 1-10 scale
            $table->timestamp('breaking_at')->nullable()->index()->after('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['is_breaking', 'priority', 'breaking_at']);
        });
    }
};
