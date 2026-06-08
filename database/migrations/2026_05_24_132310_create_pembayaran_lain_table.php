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
        if (!Schema::hasTable('pembayaran_lain')) {
            Schema::create('pembayaran_lain', function (Blueprint $table) {
                $table->id();
                
                // Foreign key ke siswa (gunakan 'siswa' atau 'siswas' sesuai database Anda)
                $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
                
                $table->string('kode_pembayaran', 50)->unique();
                $table->string('nama_pembayaran', 100);
                $table->text('deskripsi')->nullable();
                $table->decimal('nominal', 15, 2);
                $table->decimal('jumlah_dibayar', 15, 2)->default(0);
                $table->decimal('sisa', 15, 2)->default(0);
                
                // Status pembayaran
                $table->string('status', 20)->default('belum_lunas');
                
                // Tanggal-tanggal penting
                $table->date('tanggal_jatuh_tempo')->nullable();
                $table->date('tanggal_bayar')->nullable();
                
                // Informasi pembayaran
                $table->string('metode_pembayaran', 50)->nullable();
                $table->string('bukti_pembayaran', 255)->nullable();
                $table->text('keterangan')->nullable();
                
                // Pencatat pembayaran
                $table->foreignId('dibayar_oleh')->nullable()->constrained('users')->onDelete('set null');
                
                // Untuk pembayaran berulang (opsional)
                $table->boolean('is_berulang')->default(false);
                $table->string('periode', 20)->nullable(); // bulanan, tahunan, semester
                
                $table->timestamps();
                $table->softDeletes();
                
                // Index untuk performa
                $table->index('siswa_id');
                $table->index('status');
                $table->index('kode_pembayaran');
                $table->index('tanggal_jatuh_tempo');
                $table->index('tanggal_bayar');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_lain');
    }
};