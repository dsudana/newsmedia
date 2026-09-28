<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('affiliate_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('destination_url');
            $table->enum('commission_type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('commission_value', 10, 2)->default(0);
            $table->unsignedBigInteger('clicks_count')->default(0);
            $table->timestamps();
        });

        Schema::create('affiliate_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_link_id')->constrained('affiliate_links')->cascadeOnDelete();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_clicks');
        Schema::dropIfExists('affiliate_links');
    }
};
