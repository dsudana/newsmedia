<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('description');
            $table->integer('order')->default(0)->after('icon');
            $table->boolean('is_active')->default(true)->after('order');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['icon', 'order', 'is_active']);
        });
    }
};
