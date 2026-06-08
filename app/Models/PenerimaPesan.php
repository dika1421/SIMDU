<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenerimaPesan extends Model
{
    use SoftDeletes;
    
    protected $table = 'penerima_pesan';
    
    protected $fillable = [
        'pesan_id',
        'penerima_id',
        'penerima_type',
        'status',
        'tanggal_baca',
        'tanggal_dihapus'
    ];
    
    protected $casts = [
        'tanggal_baca' => 'datetime',
        'tanggal_dihapus' => 'datetime'
    ];
    
    // Relasi ke pesan
    public function pesan()
    {
        return $this->belongsTo(Pesan::class);
    }
    
    // Relasi ke penerima
    public function penerima()
    {
        return $this->morphTo();
    }
    
    // Scope untuk pesan yang belum dibaca
    public function scopeUnread($query)
    {
        return $query->where('status', 'terkirim');
    }
    
    // Scope untuk pesan yang sudah dibaca
    public function scopeRead($query)
    {
        return $query->where('status', 'dibaca');
    }
}