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
        if (!Schema::hasTable('spp')) {
            Schema::create('spp', function (Blueprint $table) {
                $table->id();
                
                // Foreign key ke tabel siswa (perhatikan nama tabel: 'siswa' atau 'siswas')
                $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
                
                $table->string('bulan', 20);
                $table->integer('tahun');
                $table->decimal('nominal', 15, 2);
                $table->decimal('jumlah', 15, 2)->default(0);
                
                // Enum untuk PostgreSQL menggunakan string dengan check constraint
                $table->string('status', 20)->default('belum_lunas');
                $table->date('tanggal_jatuh_tempo');
                $table->date('tanggal_bayar')->nullable();
                $table->string('metode_pembayaran', 50)->nullable();
                $table->string('bukti_pembayaran', 255)->nullable();
                $table->text('keterangan')->nullable();
                
                // Foreign key ke tabel users
                $table->foreignId('dibayar_oleh')->nullable()->constrained('users')->onDelete('set null');
                
                $table->timestamps();
                
                // Index untuk performa query
                $table->index('siswa_id');
                $table->index('status');
                $table->index(['bulan', 'tahun']);
                $table->index('tanggal_jatuh_tempo');
                $table->index('tanggal_bayar');
                
                // Unique constraint
                $table->unique(['siswa_id', 'bulan', 'tahun'], 'spp_siswa_bulan_tahun_unique');
            });
        }
        
        // Tambahkan check constraint untuk status setelah tabel dibuat (PostgreSQL)
        if (Schema::hasTable('spp')) {
            DB::statement("ALTER TABLE spp ADD CONSTRAINT spp_status_check CHECK (status IN ('lunas', 'belum_lunas', 'menunggu'))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spp');
    }
};