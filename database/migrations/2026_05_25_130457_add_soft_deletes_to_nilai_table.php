<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('nilai')) {
            if (!Schema::hasColumn('nilai', 'deleted_at')) {
                Schema::table('nilai', function (Blueprint $table) {
                    $table->softDeletes();
                });
                echo "✅ Kolom soft delete berhasil ditambahkan ke tabel nilai\n";
            } else {
                echo "ℹ️ Kolom deleted_at sudah ada di tabel nilai\n";
            }
        } else {
            echo "⚠️ Tabel nilai tidak ditemukan, migration dilewati.\n";
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('nilai') && Schema::hasColumn('nilai', 'deleted_at')) {
            Schema::table('nilai', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
            echo "✅ Kolom soft delete berhasil dihapus dari tabel nilai\n";
        }
    }
};