<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah')->nullable();
            $table->string('nuptk')->nullable();
            $table->string('akreditasi')->nullable();
            $table->string('kepala_sekolah')->nullable();
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            // Keamanan
            $table->boolean('two_factor')->default(false);
            $table->boolean('notifikasi_login')->default(false);
            $table->integer('masa_berlaku_password')->default(90);
            $table->integer('batas_percobaan_login')->default(5);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_sekolah');
    }
};