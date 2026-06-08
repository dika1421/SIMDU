<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah tabel keuangan ada
        if (Schema::hasTable('keuangan')) {
            // Tambahkan kolom yang hilang
            Schema::table('keuangan', function (Blueprint $table) {
                if (!Schema::hasColumn('keuangan', 'no_transaksi')) {
                    $table->string('no_transaksi', 50)->nullable()->after('id');
                }
                
                if (!Schema::hasColumn('keuangan', 'keterangan')) {
                    $table->text('keterangan')->nullable()->after('jumlah');
                }
                
                if (!Schema::hasColumn('keuangan', 'metode_bayar')) {
                    $table->string('metode_bayar', 50)->nullable()->after('keterangan');
                }
                
                if (!Schema::hasColumn('keuangan', 'siswa_id')) {
                    $table->foreignId('siswa_id')->nullable()->constrained('siswas')->onDelete('set null')->after('metode_bayar');
                }
                
                if (!Schema::hasColumn('keuangan', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        } else {
            // Buat tabel baru jika belum ada
            Schema::create('keuangan', function (Blueprint $table) {
                $table->id();
                $table->string('no_transaksi', 50)->unique();
                $table->date('tanggal');
                $table->enum('tipe', ['pemasukan', 'pengeluaran']);
                $table->string('kategori', 100);
                $table->decimal('jumlah', 15, 2);
                $table->text('keterangan')->nullable();
                $table->string('metode_bayar', 50)->nullable();
                $table->foreignId('siswa_id')->nullable()->constrained('siswas')->onDelete('set null');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::table('keuangan', function (Blueprint $table) {
            $columns = ['no_transaksi', 'keterangan', 'metode_bayar', 'siswa_id', 'deleted_at'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('keuangan', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};