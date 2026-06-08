<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('struktur_organisasi')) {
            Schema::create('struktur_organisasi', function (Blueprint $table) {
                $table->id();
                $table->string('nama_jabatan');
                $table->string('nama_pejabat');
                $table->string('nip')->nullable();
                $table->text('deskripsi_tugas')->nullable();
                $table->integer('urutan')->default(0);
                $table->foreignId('parent_id')->nullable()->constrained('struktur_organisasi')->onDelete('cascade');
                $table->string('foto')->nullable();
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
                $table->timestamps();
                
                $table->index('parent_id');
                $table->index('urutan');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('struktur_organisasi');
    }
};