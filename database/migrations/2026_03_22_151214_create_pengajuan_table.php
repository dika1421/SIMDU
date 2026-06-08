<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pengajuan')) {
            Schema::create('pengajuan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('judul');
                $table->enum('jenis', ['izin', 'cuti', 'mutasi', 'pindah', 'lainnya']);
                $table->text('deskripsi');
                $table->date('tanggal_mulai')->nullable();
                $table->date('tanggal_selesai')->nullable();
                $table->string('dokumen')->nullable();
                $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
                $table->text('alasan_tolak')->nullable();
                $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('tanggal_approve')->nullable();
                $table->timestamps();
                
                $table->index('user_id');
                $table->index('status');
                $table->index('jenis');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};