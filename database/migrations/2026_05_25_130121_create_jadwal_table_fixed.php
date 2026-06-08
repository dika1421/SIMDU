<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('jadwal');
        
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade'); // Gunakan 'gurus'
            $table->foreignId('mapel_id')->constrained('mapel')->onDelete('cascade');
            $table->string('hari', 20);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruangan', 50)->nullable();
            $table->string('mata_pelajaran', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status', 20)->default('aktif');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('kelas_id');
            $table->index('guru_id');
            $table->index('mapel_id');
            $table->index('hari');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};