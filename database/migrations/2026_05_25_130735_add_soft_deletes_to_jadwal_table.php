<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('jadwal')) {
            if (!Schema::hasColumn('jadwal', 'deleted_at')) {
                Schema::table('jadwal', function (Blueprint $table) {
                    $table->softDeletes();
                });
                echo "✅ Kolom soft delete berhasil ditambahkan ke tabel jadwal\n";
            }
        } else {
            echo "⚠️ Tabel jadwal tidak ditemukan\n";
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('jadwal') && Schema::hasColumn('jadwal', 'deleted_at')) {
            Schema::table('jadwal', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
            echo "✅ Kolom soft delete berhasil dihapus dari tabel jadwal\n";
        }
    }
};