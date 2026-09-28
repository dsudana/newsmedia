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
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamp('subscribed_at')->useCurrent();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();
            $table->index('is_active');
        });

        Schema::create('newsletter_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('subject');
            $table->longText('html_content');
            $table->string('type')->default('manual'); // manual, daily, weekly
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('newsletter_sends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('newsletter_templates')->cascadeOnDelete();
            $table->integer('total_subscribers')->default(0);
            $table->integer('sent_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->enum('status', ['pending', 'sending', 'completed', 'failed'])->default('pending');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_sends');
        Schema::dropIfExists('newsletter_templates');
        Schema::dropIfExists('newsletter_subscribers');
    }
};
