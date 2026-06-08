<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('gurus')) {
            Schema::create('gurus', function (Blueprint $table) {
                $table->id();
                
                // Relasi ke user
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                
                // Data pribadi
                $table->string('nip', 50)->unique();
                $table->string('nuptk', 50)->nullable()->unique();
                $table->string('nama_lengkap');
                $table->enum('jenis_kelamin', ['L', 'P']);
                $table->string('tempat_lahir');
                $table->date('tanggal_lahir');
                $table->text('alamat')->nullable();
                $table->string('no_telepon', 20)->nullable();
                
                // Pendidikan
                $table->string('pendidikan_terakhir', 100);
                $table->string('jurusan_pendidikan', 100)->nullable();
                $table->string('universitas', 100)->nullable();
                $table->year('tahun_lulus')->nullable();
                
                // Kepegawaian
                $table->date('tmt_masuk')->nullable();
                $table->enum('status_kepegawaian', ['pns', 'pppk', 'honorer', 'kontrak'])->default('honorer');
                $table->string('golongan', 10)->nullable();
                
                // Bidang
                $table->string('mata_pelajaran_utama', 100)->nullable();
                $table->text('keahlian_khusus')->nullable();
                
                // Status
                $table->enum('status', ['aktif', 'nonaktif', 'cuti', 'pensiun'])->default('aktif');
                
                $table->timestamps();
                $table->softDeletes();
                
                // Index
                $table->index('user_id');
                $table->index('nip');
                $table->index('status');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};