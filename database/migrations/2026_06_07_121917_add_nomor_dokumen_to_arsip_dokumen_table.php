<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNomorDokumenToArsipDokumenTable extends Migration
{
    public function up()
    {
        Schema::table('arsip_dokumen', function (Blueprint $table) {
            if (!Schema::hasColumn('arsip_dokumen', 'nomor_dokumen')) {
                $table->string('nomor_dokumen', 100)->nullable()->after('id');
            }
            if (!Schema::hasColumn('arsip_dokumen', 'tanggal_dokumen')) {
                $table->date('tanggal_dokumen')->nullable()->after('kategori');
            }
            if (!Schema::hasColumn('arsip_dokumen', 'uploaded_by')) {
                $table->unsignedBigInteger('uploaded_by')->nullable()->after('file_path');
                $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('arsip_dokumen', function (Blueprint $table) {
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
        });
    }
}