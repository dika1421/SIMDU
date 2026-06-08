<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('spp')) {
            
            // Tambah kolom jika belum ada
            if (!Schema::hasColumn('spp', 'no_transaksi')) {
                Schema::table('spp', function (Blueprint $table) {
                    $table->string('no_transaksi', 50)->nullable()->after('id');
                });
            }
            
            if (!Schema::hasColumn('spp', 'siswa_id')) {
                Schema::table('spp', function (Blueprint $table) {
                    $table->foreignId('siswa_id')->nullable()->after('no_transaksi');
                });
            }
            
            if (!Schema::hasColumn('spp', 'tanggal_jatuh_tempo')) {
                Schema::table('spp', function (Blueprint $table) {
                    $table->date('tanggal_jatuh_tempo')->nullable()->after('status');
                });
            }
            
            if (!Schema::hasColumn('spp', 'tanggal_bayar')) {
                Schema::table('spp', function (Blueprint $table) {
                    $table->date('tanggal_bayar')->nullable()->after('tanggal_jatuh_tempo');
                });
            }
            
            if (!Schema::hasColumn('spp', 'metode_pembayaran')) {
                Schema::table('spp', function (Blueprint $table) {
                    $table->string('metode_pembayaran', 50)->nullable()->after('tanggal_bayar');
                });
            }
            
            if (!Schema::hasColumn('spp', 'bukti_pembayaran')) {
                Schema::table('spp', function (Blueprint $table) {
                    $table->string('bukti_pembayaran', 255)->nullable()->after('metode_pembayaran');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('spp')) {
            $columns = ['no_transaksi', 'siswa_id', 'tanggal_jatuh_tempo', 'tanggal_bayar', 'metode_pembayaran', 'bukti_pembayaran'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('spp', $column)) {
                    Schema::table('spp', function (Blueprint $table) use ($column) {
                        $table->dropColumn($column);
                    });
                }
            }
        }
    }
};