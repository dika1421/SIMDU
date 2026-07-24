<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ganti 'siswa' jadi 'siswas' kalau di DB kamu tabelnya siswas
        if (Schema::hasTable('siswas')) {
            if (!Schema::hasColumn('siswas', 'rfid')) {
                Schema::table('siswas', function (Blueprint $table) {
                    $table->string('rfid', 50)->nullable()->after('jenis_kelamin');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('siswas') && Schema::hasColumn('siswas', 'rfid')) {
            Schema::table('siswas', function (Blueprint $table) {
                $table->dropColumn('rfid');
            });
        }
    }
};