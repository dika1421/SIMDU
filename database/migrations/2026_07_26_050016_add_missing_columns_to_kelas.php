<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Cek satu-satu biar gak error duplicate column
            if (!Schema::hasColumn('kelas', 'kode_kelas')) {
                $table->string('kode_kelas')->nullable()->after('nama_kelas');
            }
            if (!Schema::hasColumn('kelas', 'wali_kelas_id')) {
                $table->unsignedBigInteger('wali_kelas_id')->nullable()->after('jurusan_id');
            }
            if (!Schema::hasColumn('kelas', 'kapasitas')) {
                $table->integer('kapasitas')->default(40)->after('tingkat');
            }
            if (!Schema::hasColumn('kelas', 'tahun_ajaran')) {
                $table->string('tahun_ajaran', 20)->nullable()->after('kapasitas');
            }
            if (!Schema::hasColumn('kelas', 'status')) {
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('tahun_ajaran');
            }
            if (!Schema::hasColumn('kelas', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn(['kode_kelas', 'wali_kelas_id', 'kapasitas', 'tahun_ajaran', 'status', 'keterangan']);
        });
    }
};