<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pesan extends Model
{
    use SoftDeletes;
    
    protected $table = 'pesan';
    
    protected $fillable = [
        'judul',
        'isi',
        'jenis',
        'pengirim_id',
        'pengirim_type',
        'status',
        'is_urgent',
        'is_attachment',
        'attachment_path',
        'attachment_name',
        'attachment_type',
        'attachment_size',
        'tanggal_kirim',
        'tanggal_baca',
        'tanggal_dihapus'
    ];
    
    protected $casts = [
        'is_urgent' => 'boolean',
        'is_attachment' => 'boolean',
        'attachment_size' => 'integer',
        'tanggal_kirim' => 'datetime',
        'tanggal_baca' => 'datetime',
        'tanggal_dihapus' => 'datetime'
    ];
    
    // Relasi ke pengirim
    public function pengirim()
    {
        return $this->morphTo();
    }
    
    // Relasi ke penerima
    public function penerima()
    {
        return $this->belongsToMany(User::class, 'penerima_pesan', 'pesan_id', 'penerima_id')
                    ->withPivot('status', 'tanggal_baca', 'tanggal_dihapus')
                    ->withTimestamps();
    }
    
    // Relasi ke penerima pesan
    public function penerimaPesan()
    {
        return $this->hasMany(PenerimaPesan::class);
    }
    
    // Scope untuk pesan yang belum dibaca
    public function scopeUnread($query, $userId)
    {
        return $query->whereHas('penerimaPesan', function($q) use ($userId) {
            $q->where('penerima_id', $userId)
              ->where('status', 'terkirim');
        });
    }
    
    // Scope untuk pesan yang sudah dibaca
    public function scopeRead($query, $userId)
    {
        return $query->whereHas('penerimaPesan', function($q) use ($userId) {
            $q->where('penerima_id', $userId)
              ->where('status', 'dibaca');
        });
    }
    
    // Method untuk menandai sebagai dibaca
    public function markAsRead($userId)
    {
        return $this->penerimaPesan()
                    ->where('penerima_id', $userId)
                    ->update([
                        'status' => 'dibaca',
                        'tanggal_baca' => now()
                    ]);
    }
    
    // Method untuk mendapatkan jumlah pesan belum dibaca
    public static function getUnreadCount($userId)
    {
        return self::whereHas('penerimaPesan', function($q) use ($userId) {
            $q->where('penerima_id', $userId)
              ->where('status', 'terkirim');
        })->count();
    }
}