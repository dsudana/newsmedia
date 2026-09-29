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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nama iklan');
            $table->string('placement')->comment('Posisi: header_banner, sidebar_top, sidebar_bottom, content_middle');
            $table->string('image')->nullable()->comment('Path gambar iklan');
            $table->string('url')->nullable()->comment('URL tujuan iklan');
            $table->text('description')->nullable()->comment('Deskripsi iklan');
            $table->string('size')->comment('Ukuran: 1200x128 (header), 300x250 (sidebar), 300x600 (sidebar besar), custom');
            $table->integer('width')->comment('Lebar pixel');
            $table->integer('height')->comment('Tinggi pixel');
            $table->boolean('is_active')->default(true)->comment('Status aktif/nonaktif');
            $table->timestamp('start_date')->nullable()->comment('Tanggal mulai tampil');
            $table->timestamp('end_date')->nullable()->comment('Tanggal berhenti tampil');
            $table->integer('click_count')->default(0)->comment('Jumlah klik');
            $table->integer('view_count')->default(0)->comment('Jumlah views');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
