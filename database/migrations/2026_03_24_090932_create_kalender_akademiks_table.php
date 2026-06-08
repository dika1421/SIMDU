<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kalender_akademiks')) {
            Schema::create('kalender_akademiks', function (Blueprint $table) {
                $table->id();
                
                // Informasi event akademik
                $table->string('judul', 255);
                $table->text('deskripsi')->nullable();
                $table->enum('jenis', [
                    'libur', 
                    'ujian', 
                    'pendaftaran', 
                    'acara', 
                    'rapat', 
                    'ekstrakurikuler',
                    'lainnya'
                ])->default('lainnya');
                
                // Tanggal event
                $table->date('tanggal_mulai');
                $table->date('tanggal_selesai')->nullable();
                $table->time('waktu_mulai')->nullable();
                $table->time('waktu_selesai')->nullable();
                
                // Tahun ajaran dan semester
                $table->string('tahun_ajaran', 20);
                $table->enum('semester', ['ganjil', 'genap'])->nullable();
                
                // Lokasi
                $table->string('lokasi', 255)->nullable();
                $table->string('tempat', 255)->nullable();
                
                // Status dan validasi
                $table->enum('status', ['aktif', 'nonaktif', 'selesai'])->default('aktif');
                $table->boolean('is_nasional')->default(false);
                $table->boolean('is_wajib')->default(false);
                
                // Warna untuk display di kalender
                $table->string('warna', 20)->default('#007bff');
                $table->string('icon', 50)->nullable();
                
                // Target peserta
                $table->enum('target', ['semua', 'guru', 'siswa', 'staf'])->default('semua');
                
                // Informasi tambahan
                $table->text('keterangan')->nullable();
                $table->string('link_pendaftaran')->nullable();
                $table->string('dokumen_pendukung')->nullable();
                
                // Relasi
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
                
                $table->timestamps();
                $table->softDeletes();
                
                // Index untuk optimasi query
                $table->index('tanggal_mulai');
                $table->index('tanggal_selesai');
                $table->index('jenis');
                $table->index('status');
                $table->index('tahun_ajaran');
                $table->index('semester');
                $table->index(['tanggal_mulai', 'tanggal_selesai']);
                $table->index(['tahun_ajaran', 'semester']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kalender_akademiks');
    }
};