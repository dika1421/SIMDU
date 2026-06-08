<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('ruang_kelas')) {
            Schema::create('ruang_kelas', function (Blueprint $table) {
                $table->id();
                
                // Informasi ruang kelas
                $table->string('kode_ruang', 20)->unique();
                $table->string('nama_ruang', 100);
                $table->integer('kapasitas')->default(36);
                
                // Lokasi dan fasilitas
                $table->string('gedung', 50)->nullable();
                $table->string('lantai', 10)->nullable();
                $table->text('fasilitas')->nullable(); // AC, LCD, dll
                
                // Status
                $table->string('status', 20)->default('aktif');
                $table->text('keterangan')->nullable();
                
                $table->timestamps();
                $table->softDeletes();
                
                // Index
                $table->index('status');
                $table->index('gedung');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruang_kelas');
    }
};