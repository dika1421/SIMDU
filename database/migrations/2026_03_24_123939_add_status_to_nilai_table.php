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
        // Cek apakah tabel nilai ada
        if (Schema::hasTable('nilai')) {
            // Cek apakah kolom status sudah ada
            if (!Schema::hasColumn('nilai', 'status')) {
                Schema::table('nilai', function (Blueprint $table) {
                    $table->enum('status', ['draft', 'published', 'revisi'])->default('draft');
                });
            }
        } else {
            // Jika tabel belum ada, buat dengan status
            Schema::create('nilai', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('siswa_id');
                $table->unsignedBigInteger('mapel_id');
                $table->unsignedBigInteger('guru_id');
                $table->integer('nilai_angka')->nullable();
                $table->string('nilai_huruf')->nullable();
                $table->enum('status', ['draft', 'published', 'revisi'])->default('draft');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            if (Schema::hasColumn('nilai', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};