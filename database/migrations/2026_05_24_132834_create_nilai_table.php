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
        if (!Schema::hasTable('nilai')) {
            Schema::create('nilai', function (Blueprint $table) {
                $table->id();
                
                // Foreign keys
                $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
                $table->foreignId('mapel_id')->constrained('mapel')->onDelete('cascade');
                $table->foreignId('guru_id')->constrained('guru')->onDelete('cascade');
                $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
                $table->foreignId('tahun_ajaran_id')->nullable()->constrained('tahun_ajaran')->onDelete('set null');
                
                // Jenis nilai
                $table->string('jenis', 20)->default('harian'); // harian, uts, uas, praktik, tugas
                
                // Nilai
                $table->decimal('nilai', 10, 2)->nullable();
                $table->decimal('nilai_akhir', 10, 2)->nullable();
                
                // Nilai komponen (opsional)
                $table->decimal('nilai_harian_1', 10, 2)->nullable();
                $table->decimal('nilai_harian_2', 10, 2)->nullable();
                $table->decimal('nilai_harian_3', 10, 2)->nullable();
                $table->decimal('nilai_tugas_1', 10, 2)->nullable();
                $table->decimal('nilai_tugas_2', 10, 2)->nullable();
                $table->decimal('nilai_uts', 10, 2)->nullable();
                $table->decimal('nilai_uas', 10, 2)->nullable();
                $table->decimal('nilai_praktek', 10, 2)->nullable();
                
                // Predikat dan deskripsi
                $table->string('predikat', 10)->nullable(); // A, B, C, D
                $table->text('deskripsi')->nullable();
                $table->text('catatan')->nullable();
                $table->text('catatan_guru')->nullable();
                $table->text('catatan_wali')->nullable();
                
                // Status
                $table->string('status', 20)->default('draft'); // draft, published
                $table->boolean('is_rapor')->default(false);
                
                // Kurikulum dan periode
                $table->string('kurikulum', 20)->nullable(); // K13, Kurikulum Merdeka
                $table->string('tahun_ajaran', 20)->nullable();
                $table->string('semester', 10)->nullable(); // ganjil, genap
                
                $table->timestamps();
                $table->softDeletes();
                
                // Index untuk performa
                $table->index('siswa_id');
                $table->index('mapel_id');
                $table->index('guru_id');
                $table->index('kelas_id');
                $table->index('jenis');
                $table->index('status');
                $table->index(['siswa_id', 'mapel_id', 'tahun_ajaran_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};