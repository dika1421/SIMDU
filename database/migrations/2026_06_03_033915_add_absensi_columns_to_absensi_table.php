<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAbsensiColumnsToAbsensiTable extends Migration
{
    public function up()
    {
        Schema::table('absensi', function (Blueprint $table) {
            // Tambahkan kolom absensi_type jika belum ada
            if (!Schema::hasColumn('absensi', 'absensi_type')) {
                $table->enum('absensi_type', ['siswa', 'guru'])->after('id')->nullable();
            }
            
            // Tambahkan kolom siswa_id jika belum ada
            if (!Schema::hasColumn('absensi', 'siswa_id')) {
                $table->unsignedBigInteger('siswa_id')->nullable()->after('absensi_type');
                $table->foreign('siswa_id')->references('id')->on('siswa')->onDelete('cascade');
            }
            
            // Tambahkan kolom guru_id jika belum ada
            if (!Schema::hasColumn('absensi', 'guru_id')) {
                $table->unsignedBigInteger('guru_id')->nullable()->after('siswa_id');
                $table->foreign('guru_id')->references('id')->on('guru')->onDelete('cascade');
            }
            
            // Tambahkan kolom waktu_keluar jika belum ada
            if (!Schema::hasColumn('absensi', 'waktu_keluar')) {
                $table->time('waktu_keluar')->nullable()->after('waktu_masuk');
            }
            
            // Tambahkan index untuk performance
            $table->index(['absensi_type', 'tanggal']);
            $table->index(['siswa_id', 'tanggal']);
            $table->index(['guru_id', 'tanggal']);
        });
    }

    public function down()
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropIndex(['absensi_type', 'tanggal']);
            $table->dropIndex(['siswa_id', 'tanggal']);
            $table->dropIndex(['guru_id', 'tanggal']);
            
            if (Schema::hasColumn('absensi', 'guru_id')) {
                $table->dropForeign(['guru_id']);
                $table->dropColumn('guru_id');
            }
            
            if (Schema::hasColumn('absensi', 'siswa_id')) {
                $table->dropForeign(['siswa_id']);
                $table->dropColumn('siswa_id');
            }
            
            if (Schema::hasColumn('absensi', 'absensi_type')) {
                $table->dropColumn('absensi_type');
            }
            
            if (Schema::hasColumn('absensi', 'waktu_keluar')) {
                $table->dropColumn('waktu_keluar');
            }
        });
    }
}