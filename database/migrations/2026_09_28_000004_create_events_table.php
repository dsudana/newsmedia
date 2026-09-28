<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->dateTime('event_date')->comment('Date and time of the event');
            $table->dateTime('event_end_date')->nullable()->comment('End date if multi-day event');

            $table->string('location')->nullable();
            $table->text('location_details')->nullable()->comment('Address, venue, etc.');

            $table->string('featured_image')->nullable();
            $table->string('event_url')->nullable()->comment('External link if applicable');

            // Categories
            $table->string('category')->nullable()->comment('e.g., workshop, seminar, social');
            $table->string('status')->default('scheduled')->comment('scheduled, ongoing, cancelled, completed');

            $table->integer('capacity')->nullable()->comment('Max attendees');
            $table->integer('registered')->default(0)->comment('Current registrations');
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('views')->default(0);

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('event_date');
            $table->index('status');
            $table->index('is_active');
            $table->index(['is_active', 'event_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
