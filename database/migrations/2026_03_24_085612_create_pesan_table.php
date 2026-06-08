<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pesan')) {
            Schema::create('pesan', function (Blueprint $table) {
                $table->id();
                
                // Informasi pesan
                $table->string('judul', 255);
                $table->text('isi');
                $table->enum('jenis', ['personal', 'broadcast', 'grup'])->default('personal');
                
                // Pengirim
                $table->foreignId('pengirim_id')->constrained('users')->onDelete('cascade');
                $table->string('pengirim_type')->default('App\\Models\\User');
                
                // Status pesan
                $table->enum('status', ['draft', 'terkirim', 'dibaca', 'dihapus'])->default('terkirim');
                $table->boolean('is_urgent')->default(false);
                $table->boolean('is_attachment')->default(false);
                
                // Lampiran
                $table->string('attachment_path')->nullable();
                $table->string('attachment_name')->nullable();
                $table->string('attachment_type')->nullable();
                $table->bigInteger('attachment_size')->nullable();
                
                // Waktu
                $table->timestamp('tanggal_kirim')->useCurrent();
                $table->timestamp('tanggal_baca')->nullable();
                $table->timestamp('tanggal_dihapus')->nullable();
                
                // Soft delete
                $table->softDeletes();
                $table->timestamps();
                
                // Index
                $table->index('pengirim_id');
                $table->index('status');
                $table->index('jenis');
                $table->index('created_at');
                $table->index(['pengirim_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pesan');
    }
};