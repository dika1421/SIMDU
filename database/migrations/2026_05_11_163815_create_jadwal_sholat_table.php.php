<?php
// database/migrations/2025_01_01_000002_create_jadwal_sholat_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalSholatTable extends Migration
{
    public function up()
    {
        Schema::create('jadwal_sholat', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->time('subuh')->nullable();
            $table->time('dzuhur')->nullable();
            $table->time('ashar')->nullable();
            $table->time('maghrib')->nullable();
            $table->time('isya')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            $table->unique('tanggal');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal_sholat');
    }
}