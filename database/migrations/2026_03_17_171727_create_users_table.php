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
        // Cek apakah tabel users sudah ada
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                
                // Kolom tambahan untuk role system
                $table->enum('role', ['kepala_sekolah', 'administrasi', 'guru', 'siswa'])->default('siswa');
                $table->string('foto')->nullable();
                $table->string('no_telepon', 20)->nullable();
                $table->text('alamat')->nullable();
                $table->enum('status', ['aktif', 'nonaktif', 'suspend'])->default('aktif');
                $table->timestamp('last_login_at')->nullable();
                $table->string('last_login_ip')->nullable();
                
                $table->timestamps();
            });
        } else {
            // Jika tabel sudah ada, tambahkan kolom yang mungkin belum ada
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'role')) {
                    $table->enum('role', ['kepala_sekolah', 'administrasi', 'guru', 'siswa'])->default('siswa');
                }
                
                if (!Schema::hasColumn('users', 'foto')) {
                    $table->string('foto')->nullable();
                }
                
                if (!Schema::hasColumn('users', 'no_telepon')) {
                    $table->string('no_telepon', 20)->nullable();
                }
                
                if (!Schema::hasColumn('users', 'alamat')) {
                    $table->text('alamat')->nullable();
                }
                
                if (!Schema::hasColumn('users', 'status')) {
                    $table->enum('status', ['aktif', 'nonaktif', 'suspend'])->default('aktif');
                }
                
                if (!Schema::hasColumn('users', 'last_login_at')) {
                    $table->timestamp('last_login_at')->nullable();
                }
                
                if (!Schema::hasColumn('users', 'last_login_ip')) {
                    $table->string('last_login_ip')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};