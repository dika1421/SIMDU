<?php
// database/migrations/2025_01_01_000001_create_absensi_sholat_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensiSholatTable extends Migration
{
    public function up()
    {
        Schema::create('absensi_sholat', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['siswa', 'guru']); // Peran pengguna
            $table->unsignedBigInteger('user_id'); // ID siswa atau guru
            $table->date('tanggal');
            $table->enum('sholat', ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya']);
            $table->enum('status', ['tepat_waktu', 'terlambat', 'tidak_hadir', 'izin']);
            $table->time('waktu_absen')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('lokasi')->nullable(); // Lokasi absen
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('foto')->nullable(); // Foto selfie absen
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index(['role', 'user_id', 'tanggal', 'sholat']);
            $table->unique(['role', 'user_id', 'tanggal', 'sholat'], 'unique_absensi_sholat');
        });
    }

    public function down()
    {
        Schema::dropIfExists('absensi_sholat');
    }
}