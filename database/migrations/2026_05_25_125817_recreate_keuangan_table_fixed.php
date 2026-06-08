<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('keuangan');
        
        Schema::create('keuangan', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi', 50)->unique();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade'); // Gunakan 'siswa', bukan 'siswas'
            $table->string('jenis_pembayaran', 50);
            $table->decimal('nominal', 15, 2);
            $table->decimal('jumlah_dibayar', 15, 2)->default(0);
            $table->decimal('sisa', 15, 2)->default(0);
            $table->string('status', 20)->default('belum_lunas');
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->date('tanggal_bayar')->nullable();
            $table->string('metode_pembayaran', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('siswa_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangan');
    }
};