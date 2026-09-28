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
        Schema::create('ad_slots', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('location'); // header, sidebar, content, footer
            $table->string('ad_code')->nullable(); // Google AdSense code
            $table->string('ad_type')->default('adsense'); // adsense, manual, affiliate
            $table->text('manual_html')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('ads_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('type')->default('string');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ads_settings');
        Schema::dropIfExists('ad_slots');
    }
};
