<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixArsipDokumenTable extends Migration
{
    public function up()
    {
        Schema::table('arsip_dokumen', function (Blueprint $table) {
            // Tambahkan kolom nama_dokumen jika belum ada
            if (!Schema::hasColumn('arsip_dokumen', 'nama_dokumen')) {
                $table->string('nama_dokumen', 255)->nullable()->after('id');
            }
            
            // Tambahkan kolom nomor_dokumen jika belum ada
            if (!Schema::hasColumn('arsip_dokumen', 'nomor_dokumen')) {
                $table->string('nomor_dokumen', 100)->nullable()->after('id');
            }
            
            // Tambahkan kolom tanggal_dokumen jika belum ada
            if (!Schema::hasColumn('arsip_dokumen', 'tanggal_dokumen')) {
                $table->date('tanggal_dokumen')->nullable()->after('kategori');
            }
            
            // Tambahkan kolom uploaded_by jika belum ada
            if (!Schema::hasColumn('arsip_dokumen', 'uploaded_by')) {
                $table->unsignedBigInteger('uploaded_by')->nullable()->after('file_path');
                $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
            }
            
            // Tambahkan soft deletes jika belum ada
            if (!Schema::hasColumn('arsip_dokumen', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down()
    {
        Schema::table('arsip_dokumen', function (Blueprint $table) {
            if (Schema::hasColumn('arsip_dokumen', 'nama_dokumen')) {
                $table->dropColumn('nama_dokumen');
            }
            if (Schema::hasColumn('arsip_dokumen', 'nomor_dokumen')) {
                $table->dropColumn('nomor_dokumen');
            }
            if (Schema::hasColumn('arsip_dokumen', 'tanggal_dokumen')) {
                $table->dropColumn('tanggal_dokumen');
            }
            if (Schema::hasColumn('arsip_dokumen', 'uploaded_by')) {
                $table->dropForeign(['uploaded_by']);
                $table->dropColumn('uploaded_by');
            }
            if (Schema::hasColumn('arsip_dokumen', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
}