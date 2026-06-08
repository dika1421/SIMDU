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
        // Cek apakah tabel sudah ada
        if (!Schema::hasTable('jabatan')) {
            Schema::create('jabatan', function (Blueprint $table) {
                $table->id();
                
                // Informasi dasar jabatan
                $table->string('nama_jabatan');
                $table->string('kode_jabatan', 50)->unique();
                $table->enum('tingkat', ['struktural', 'fungsional', 'staf', 'kepala_sekolah', 'wakil_kepala', 'guru', 'tata_usaha'])->default('staf');
                $table->enum('jenis', ['pendidikan', 'administrasi', 'pendukung'])->default('pendidikan');
                
                // Hierarki jabatan
                $table->foreignId('parent_id')->nullable()->constrained('jabatan')->onDelete('set null');
                $table->integer('level')->default(1);
                $table->integer('urutan')->default(0);
                
                // Detail jabatan
                $table->text('deskripsi')->nullable();
                $table->text('tugas_pokok')->nullable();
                $table->text('tanggung_jawab')->nullable();
                $table->text('wewenang')->nullable();
                $table->text('kualifikasi')->nullable();
                
                // Persyaratan
                $table->string('pendidikan_minimal', 100)->nullable();
                $table->integer('pengalaman_minimal')->nullable(); // dalam tahun
                $table->string('kompetensi_diperlukan')->nullable();
                
                // Status dan validasi
                $table->enum('status', ['aktif', 'nonaktif', 'draft'])->default('aktif');
                $table->boolean('is_kepala')->default(false);
                $table->boolean('is_wakil')->default(false);
                
                // Gaji dan tunjangan
                $table->decimal('gaji_pokok_min', 15, 2)->nullable();
                $table->decimal('gaji_pokok_max', 15, 2)->nullable();
                $table->decimal('tunjangan_jabatan', 15, 2)->nullable();
                
                // Informasi tambahan
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->text('keterangan')->nullable();
                
                $table->timestamps();
                $table->softDeletes(); // Soft delete untuk riwayat jabatan
                
                // Index untuk optimasi query
                $table->index('nama_jabatan');
                $table->index('kode_jabatan');
                $table->index('tingkat');
                $table->index('status');
                $table->index('parent_id');
                $table->index('level');
                $table->index('jenis');
                $table->index(['tingkat', 'status']);
                $table->index(['jenis', 'status']);
            });
        } else {
            // Jika tabel sudah ada, tambahkan kolom yang mungkin belum ada
            Schema::table('jabatan', function (Blueprint $table) {
                if (!Schema::hasColumn('jabatan', 'jenis')) {
                    $table->enum('jenis', ['pendidikan', 'administrasi', 'pendukung'])->default('pendidikan');
                }
                
                if (!Schema::hasColumn('jabatan', 'parent_id')) {
                    $table->foreignId('parent_id')->nullable()->constrained('jabatan')->onDelete('set null');
                }
                
                if (!Schema::hasColumn('jabatan', 'level')) {
                    $table->integer('level')->default(1);
                }
                
                if (!Schema::hasColumn('jabatan', 'urutan')) {
                    $table->integer('urutan')->default(0);
                }
                
                if (!Schema::hasColumn('jabatan', 'tugas_pokok')) {
                    $table->text('tugas_pokok')->nullable();
                }
                
                if (!Schema::hasColumn('jabatan', 'tanggung_jawab')) {
                    $table->text('tanggung_jawab')->nullable();
                }
                
                if (!Schema::hasColumn('jabatan', 'wewenang')) {
                    $table->text('wewenang')->nullable();
                }
                
                if (!Schema::hasColumn('jabatan', 'kualifikasi')) {
                    $table->text('kualifikasi')->nullable();
                }
                
                if (!Schema::hasColumn('jabatan', 'pendidikan_minimal')) {
                    $table->string('pendidikan_minimal', 100)->nullable();
                }
                
                if (!Schema::hasColumn('jabatan', 'pengalaman_minimal')) {
                    $table->integer('pengalaman_minimal')->nullable();
                }
                
                if (!Schema::hasColumn('jabatan', 'kompetensi_diperlukan')) {
                    $table->string('kompetensi_diperlukan')->nullable();
                }
                
                if (!Schema::hasColumn('jabatan', 'is_kepala')) {
                    $table->boolean('is_kepala')->default(false);
                }
                
                if (!Schema::hasColumn('jabatan', 'is_wakil')) {
                    $table->boolean('is_wakil')->default(false);
                }
                
                if (!Schema::hasColumn('jabatan', 'gaji_pokok_min')) {
                    $table->decimal('gaji_pokok_min', 15, 2)->nullable();
                }
                
                if (!Schema::hasColumn('jabatan', 'gaji_pokok_max')) {
                    $table->decimal('gaji_pokok_max', 15, 2)->nullable();
                }
                
                if (!Schema::hasColumn('jabatan', 'tunjangan_jabatan')) {
                    $table->decimal('tunjangan_jabatan', 15, 2)->nullable();
                }
                
                if (!Schema::hasColumn('jabatan', 'created_by')) {
                    $table->string('created_by')->nullable();
                }
                
                if (!Schema::hasColumn('jabatan', 'updated_by')) {
                    $table->string('updated_by')->nullable();
                }
                
                if (!Schema::hasColumn('jabatan', 'deleted_at')) {
                    $table->softDeletes();
                }
                
                if (!Schema::hasColumn('jabatan', 'keterangan')) {
                    $table->text('keterangan')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatan');
    }
};