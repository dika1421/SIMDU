<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateArsipDokumenTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('arsip_dokumen');
        
        Schema::create('arsip_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_dokumen', 100)->nullable();
            $table->string('nama_dokumen', 200);
            $table->enum('kategori', [
                'surat_masuk', 
                'surat_keluar', 
                'keputusan', 
                'laporan', 
                'notulen',
                'sertifikat',
                'ijazah',
                'lainnya'
            ])->default('lainnya');
            $table->date('tanggal_dokumen')->nullable();
            $table->string('file_path');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
            $table->index('kategori');
            $table->index('tanggal_dokumen');
        });
    }

    public function down()
    {
        Schema::dropIfExists('arsip_dokumen');
    }
}