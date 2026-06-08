<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('siswa')) {
            if (!Schema::hasColumn('siswa', 'rfid')) {
                Schema::table('siswa', function (Blueprint $table) {
                    $table->string('rfid', 50)->nullable();
                });
                $this->command->info('✅ Kolom RFID berhasil ditambahkan ke tabel siswa');
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('siswa') && Schema::hasColumn('siswa', 'rfid')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->dropColumn('rfid');
            });
        }
    }
};