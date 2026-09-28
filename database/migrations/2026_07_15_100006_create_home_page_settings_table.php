<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_page_settings', function (Blueprint $table) {
            $table->id();
            $table->string('section_name')->unique();
            $table->boolean('is_enabled')->default(true);
            $table->integer('order')->default(0);
            $table->integer('items_count')->default(6);
            $table->json('config')->nullable();
            $table->timestamps();

            $table->index('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_page_settings');
    }
};
