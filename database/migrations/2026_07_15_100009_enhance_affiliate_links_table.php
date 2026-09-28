<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('affiliate_links', function (Blueprint $table) {
            if (!Schema::hasColumn('affiliate_links', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('clicks_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('affiliate_links', function (Blueprint $table) {
            if (Schema::hasColumn('affiliate_links', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
