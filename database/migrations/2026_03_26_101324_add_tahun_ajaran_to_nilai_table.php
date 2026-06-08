<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            // Tambahkan kolom tahun_ajaran jika belum ada
            if (!Schema::hasColumn('nilai', 'tahun_ajaran')) {
                $table->string('tahun_ajaran', 20)->nullable()->after('semester');
            }
            
            // Tambahkan kolom kurikulum jika belum ada
            if (!Schema::hasColumn('nilai', 'kurikulum')) {
                $table->string('kurikulum', 50)->default('K13')->after('tahun_ajaran');
            }
        });
    }

    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            if (Schema::hasColumn('nilai', 'tahun_ajaran')) {
                $table->dropColumn('tahun_ajaran');
            }
            if (Schema::hasColumn('nilai', 'kurikulum')) {
                $table->dropColumn('kurikulum');
            }
        });
    }
};