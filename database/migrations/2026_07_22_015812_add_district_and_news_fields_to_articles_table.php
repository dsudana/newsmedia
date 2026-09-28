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
            if (!Schema::hasColumn('articles', 'district_id')) {
                $table->foreignId('district_id')
                    ->nullable()
                    ->after('category_id')
                    ->constrained('districts')
                    ->nullOnDelete();
            }
            if (!Schema::hasColumn('articles', 'is_breaking')) {
                $table->boolean('is_breaking')->default(false)->after('is_featured');
            }
            if (!Schema::hasColumn('articles', 'is_headline')) {
                $table->boolean('is_headline')->default(false)->after('is_breaking');
            }
            if (!Schema::hasColumn('articles', 'tags')) {
                $table->json('tags')->nullable()->after('is_headline');
            }
            if (!Schema::hasColumn('articles', 'location')) {
                $table->string('location')->nullable()->after('tags');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'district_id')) {
                $table->dropForeignIdFor(\App\Models\District::class);
                $table->dropColumn('district_id');
            }
            if (Schema::hasColumn('articles', 'is_breaking')) {
                $table->dropColumn('is_breaking');
            }
            if (Schema::hasColumn('articles', 'is_headline')) {
                $table->dropColumn('is_headline');
            }
            if (Schema::hasColumn('articles', 'tags')) {
                $table->dropColumn('tags');
            }
            if (Schema::hasColumn('articles', 'location')) {
                $table->dropColumn('location');
            }
        });
    }
};
