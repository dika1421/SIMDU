<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah tabel sudah ada
        if (Schema::hasTable('prestasi')) {
            return; // Skip jika tabel sudah ada
        }
        
        Schema::create('prestasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_prestasi');
            $table->integer('tahun');
            $table->date('tanggal')->nullable();
            $table->enum('tingkat', ['sekolah', 'kecamatan', 'kota', 'provinsi', 'nasional', 'internasional'])->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasi');
    }
};