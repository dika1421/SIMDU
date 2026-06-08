<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('absensi')) {
            Schema::create('absensi', function (Blueprint $table) {
                $table->id();
                $table->string('absensi_type', 100);
                $table->unsignedBigInteger('absensi_id');
                $table->date('tanggal');
                $table->time('waktu_masuk')->nullable();
                $table->time('waktu_keluar')->nullable();
                $table->string('status', 20)->default('hadir');
                $table->text('keterangan')->nullable();
                $table->unsignedBigInteger('diinput_oleh')->nullable();
                $table->unsignedBigInteger('mata_pelajaran_id')->nullable();
                $table->timestamps();
                
                // Index
                $table->index(['absensi_type', 'absensi_id', 'tanggal']);
                $table->index('status');
                $table->index('tanggal');
                
                // Foreign key (opsional, jika tabel mata_pelajaran ada)
                // $table->foreign('mata_pelajaran_id')->references('id')->on('mata_pelajarans')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};