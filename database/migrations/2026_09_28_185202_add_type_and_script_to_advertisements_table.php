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
        Schema::table('advertisements', function (Blueprint $table) {
            $table->string('type')->default('banner')->after('placement')->comment('Tipe: banner, adsense, script');
            $table->longText('script')->nullable()->after('description')->comment('Script code untuk AdSense atau custom script');
            $table->string('image')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('script');
        });
    }
};
