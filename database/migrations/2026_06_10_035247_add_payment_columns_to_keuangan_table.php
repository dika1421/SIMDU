<?php
// database/migrations/xxxx_add_payment_columns_to_keuangan_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentColumnsToKeuanganTable extends Migration
{
    public function up()
    {
        Schema::table('keuangan', function (Blueprint $table) {
            // Cek dan tambahkan kolom untuk pembayaran siswa
            if (!Schema::hasColumn('keuangan', 'jenis_pembayaran')) {
                $table->string('jenis_pembayaran')->nullable()->after('kategori');
            }
            
            if (!Schema::hasColumn('keuangan', 'tagihan')) {
                $table->decimal('tagihan', 15, 2)->default(0)->after('jumlah');
            }
            
            if (!Schema::hasColumn('keuangan', 'terbayar')) {
                $table->decimal('terbayar', 15, 2)->default(0)->after('tagihan');
            }
            
            if (!Schema::hasColumn('keuangan', 'sisa')) {
                $table->decimal('sisa', 15, 2)->default(0)->after('terbayar');
            }
            
            if (!Schema::hasColumn('keuangan', 'status_pembayaran')) {
                $table->string('status_pembayaran')->default('belum_lunas')->after('sisa');
            }
            
            if (!Schema::hasColumn('keuangan', 'tanggal_jatuh_tempo')) {
                $table->date('tanggal_jatuh_tempo')->nullable()->after('status_pembayaran');
            }
            
            if (!Schema::hasColumn('keuangan', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('user_id')->constrained('users');
            }
            
            // Index untuk performa query
            $table->index(['siswa_id', 'status_pembayaran']);
            $table->index('jenis_pembayaran');
        });
    }

    public function down()
    {
        Schema::table('keuangan', function (Blueprint $table) {
            $columns = [
                'jenis_pembayaran', 'tagihan', 'terbayar', 'sisa',
                'status_pembayaran', 'tanggal_jatuh_tempo', 'created_by'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('keuangan', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}