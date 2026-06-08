<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('absensis')) {
            Schema::create('absensis', function (Blueprint $table) {
                $table->id();
                
                // Polymorphic relation
                $table->string('absensi_type');
                $table->unsignedBigInteger('absensi_id');
                
                // Detail absensi
                $table->date('tanggal');
                $table->enum('status', ['hadir', 'sakit', 'izin', 'alfa', 'terlambat'])->default('hadir');
                $table->time('waktu_masuk')->nullable();
                $table->time('waktu_keluar')->nullable();
                $table->text('keterangan')->nullable();
                $table->text('alasan')->nullable();
                
                // Dokumen
                $table->string('dokumen_pendukung')->nullable();
                
                // Approve - menggunakan unsignedBigInteger dulu
                $table->unsignedBigInteger('diinput_oleh')->nullable();
                $table->unsignedBigInteger('disetujui_oleh')->nullable();
                $table->timestamp('waktu_approve')->nullable();
                $table->enum('status_approve', ['pending', 'disetujui', 'ditolak'])->default('pending');
                
                $table->timestamps();
                $table->softDeletes();
                
                // Index
                $table->index(['absensi_type', 'absensi_id']);
                $table->index('tanggal');
                $table->index('status');
                $table->index('diinput_oleh');
                $table->index('disetujui_oleh');
            });
        }
        
        // Tambahkan foreign key setelah tabel users dipastikan ada
        if (Schema::hasTable('users') && Schema::hasTable('absensis')) {
            try {
                Schema::table('absensis', function (Blueprint $table) {
                    if (Schema::hasColumn('absensis', 'diinput_oleh')) {
                        $table->foreign('diinput_oleh')
                              ->references('id')
                              ->on('users')
                              ->onDelete('set null');
                    }
                    
                    if (Schema::hasColumn('absensis', 'disetujui_oleh')) {
                        $table->foreign('disetujui_oleh')
                              ->references('id')
                              ->on('users')
                              ->onDelete('set null');
                    }
                });
            } catch (\Exception $e) {
                // Abaikan error foreign key, akan ditambahkan nanti
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};