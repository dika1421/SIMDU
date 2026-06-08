<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('arsip_dokumen');
        
        Schema::create('arsip_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('kode_arsip', 50)->unique();
            $table->string('judul', 200);
            $table->string('jenis_dokumen', 100);
            $table->text('deskripsi')->nullable();
            $table->string('nama_file', 255);
            $table->string('path_file', 500);
            $table->string('tipe_file', 50);
            $table->integer('ukuran_file')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('siswa_id')->nullable()->constrained('siswa')->onDelete('cascade');
            $table->foreignId('guru_id')->nullable()->constrained('gurus')->onDelete('cascade'); // Gunakan 'gurus'
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
            $table->string('status', 20)->default('aktif');
            $table->string('kategori', 50)->nullable();
            $table->year('tahun')->nullable();
            $table->date('tanggal_dokumen')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('kode_arsip');
            $table->index('jenis_dokumen');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsip_dokumen');
    }
};