<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixAbsensiTableColumns extends Migration
{
    public function up()
    {
        // Hapus constraint secara aman di PostgreSQL
        DB::statement('ALTER TABLE absensi DROP CONSTRAINT IF EXISTS absensi_absensi_id_foreign');
        DB::statement('ALTER TABLE absensi DROP CONSTRAINT IF EXISTS absensi_siswa_id_foreign');
        DB::statement('ALTER TABLE absensi DROP CONSTRAINT IF EXISTS absensi_guru_id_foreign');
        DB::statement('ALTER TABLE absensi DROP CONSTRAINT IF EXISTS absensi_mata_pelajaran_id_foreign');

        Schema::table('absensi', function (Blueprint $table) {
            // Hapus kolom absensi_id jika ada
            if (Schema::hasColumn('absensi', 'absensi_id')) {
                $table->dropColumn('absensi_id');
            }
            
            // Kalau absensi_type sudah ada, jangan dibuat lagi
            // Kalau belum ada baru buat
            if (!Schema::hasColumn('absensi', 'absensi_type')) {
                $table->enum('absensi_type', ['siswa', 'guru'])->nullable()->after('id');
            }
            
            // siswa_id - FIX ke siswas
            if (!Schema::hasColumn('absensi', 'siswa_id')) {
                $table->unsignedBigInteger('siswa_id')->nullable()->after('absensi_type');
                $table->foreign('siswa_id')->references('id')->on('siswas')->onDelete('cascade');
            }
            
            // guru_id - FIX ke gurus
            if (!Schema::hasColumn('absensi', 'guru_id')) {
                $table->unsignedBigInteger('guru_id')->nullable()->after('siswa_id');
                $table->foreign('guru_id')->references('id')->on('gurus')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('absensi', 'waktu_keluar')) {
                $table->time('waktu_keluar')->nullable()->after('waktu_masuk');
            }
        });
    }

    public function down()
    {
        // kosongkan saja biar tidak error rollback
    }
}