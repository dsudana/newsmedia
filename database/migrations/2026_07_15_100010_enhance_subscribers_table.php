<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->string('verification_token')->unique()->nullable()->after('is_active');
            $table->string('unsubscribe_token')->unique()->nullable()->after('verification_token');
            $table->timestamp('verified_at')->nullable()->after('unsubscribe_token');
            $table->timestamp('token_expires_at')->nullable()->after('verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->dropColumn(['verification_token', 'unsubscribe_token', 'verified_at', 'token_expires_at']);
        });
    }
};
