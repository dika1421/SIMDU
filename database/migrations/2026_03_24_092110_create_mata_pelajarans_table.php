<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('mata_pelajarans')) {
            Schema::create('mata_pelajarans', function (Blueprint $table) {
                $table->id();
                
                // Informasi dasar
                $table->string('kode_mapel', 20)->unique();
                $table->string('nama_mapel', 100);
                $table->string('nama_singkat', 50)->nullable();
                
                // Kelompok mapel
                $table->enum('kelompok', ['A', 'B', 'C'])->default('A');
                // A: Umum, B: Kejuruan, C: Muatan Lokal
                
                // Detail mapel
                $table->integer('jam_per_minggu')->default(2);
                $table->integer('jam_per_tahun')->nullable();
                $table->enum('jenis', ['teori', 'praktek', 'teori_praktek'])->default('teori');
                
                // Kurikulum
                $table->string('kurikulum', 50)->default('K13');
                $table->string('tingkat', 10)->nullable(); // X, XI, XII
                $table->string('jurusan', 50)->nullable(); // IPA, IPS, dll
                
                // Status
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
                $table->boolean('is_wajib')->default(true);
                $table->boolean('is_ujian_nasional')->default(false);
                
                // Deskripsi
                $table->text('deskripsi')->nullable();
                $table->text('kompetensi_dasar')->nullable();
                $table->text('silabus')->nullable();
                
                // Nilai minimal ketuntasan
                $table->decimal('kkm', 5, 2)->default(75.00);
                
                // Timestamps
                $table->timestamps();
                $table->softDeletes();
                
                // Index
                $table->index('kode_mapel');
                $table->index('nama_mapel');
                $table->index('status');
                $table->index('kelompok');
                $table->index('kurikulum');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('mata_pelajarans');
    }
};