<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah tabel pesan sudah ada
        if (!Schema::hasTable('pesan')) {
            // Jika tabel pesan belum ada, buat dulu
            $this->createPesanTable();
        }
        
        // Nonaktifkan foreign key checks untuk PostgreSQL
        DB::statement('SET CONSTRAINTS ALL DEFERRED');
        
        if (!Schema::hasTable('penerima_pesan')) {
            Schema::create('penerima_pesan', function (Blueprint $table) {
                $table->id();
                
                // Relasi
                $table->foreignId('pesan_id')->constrained('pesan')->onDelete('cascade');
                $table->foreignId('penerima_id')->constrained('users')->onDelete('cascade');
                $table->string('penerima_type')->default('App\\Models\\User');
                
                // Status penerima
                $table->enum('status', ['terkirim', 'dibaca', 'dihapus'])->default('terkirim');
                $table->timestamp('tanggal_baca')->nullable();
                $table->timestamp('tanggal_dihapus')->nullable();
                
                // Soft delete
                $table->softDeletes();
                $table->timestamps();
                
                // Index
                $table->index('pesan_id');
                $table->index('penerima_id');
                $table->index('status');
                $table->unique(['pesan_id', 'penerima_id'], 'unique_penerima_pesan');
            });
        }
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET CONSTRAINTS ALL IMMEDIATE');
    }
    
    private function createPesanTable()
    {
        Schema::create('pesan', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 255);
            $table->text('isi');
            $table->enum('jenis', ['personal', 'broadcast', 'grup'])->default('personal');
            $table->foreignId('pengirim_id')->constrained('users')->onDelete('cascade');
            $table->string('pengirim_type')->default('App\\Models\\User');
            $table->enum('status', ['draft', 'terkirim', 'dibaca', 'dihapus'])->default('terkirim');
            $table->boolean('is_urgent')->default(false);
            $table->boolean('is_attachment')->default(false);
            $table->string('attachment_path')->nullable();
            $table->string('attachment_name')->nullable();
            $table->string('attachment_type')->nullable();
            $table->bigInteger('attachment_size')->nullable();
            $table->timestamp('tanggal_kirim')->useCurrent();
            $table->timestamp('tanggal_baca')->nullable();
            $table->timestamp('tanggal_dihapus')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->index('pengirim_id');
            $table->index('status');
            $table->index('jenis');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('penerima_pesan');
    }
};