<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KalenderAkademik extends Model
{
    use SoftDeletes;
    
    protected $table = 'kalender_akademiks';
    
    protected $fillable = [
        'judul',
        'deskripsi',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'tahun_ajaran',
        'semester',
        'lokasi',
        'tempat',
        'status',
        'is_nasional',
        'is_wajib',
        'warna',
        'icon',
        'target',
        'keterangan',
        'link_pendaftaran',
        'dokumen_pendukung',
        'created_by',
        'updated_by'
    ];
    
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'is_nasional' => 'boolean',
        'is_wajib' => 'boolean'
    ];
    
    // Relasi ke user pembuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    // Relasi ke user pengupdate
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    // Scope untuk event aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
    
    // Scope berdasarkan tahun
    public function scopeTahun($query, $tahun)
    {
        return $query->whereYear('tanggal_mulai', $tahun)
                     ->orWhereYear('tanggal_selesai', $tahun);
    }
    
    // Scope berdasarkan bulan
    public function scopeBulan($query, $bulan)
    {
        return $query->whereMonth('tanggal_mulai', $bulan)
                     ->orWhereMonth('tanggal_selesai', $bulan);
    }
    
    // Scope berdasarkan jenis
    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }
    
    // Scope berdasarkan tahun ajaran
    public function scopeTahunAjaran($query, $tahunAjaran)
    {
        return $query->where('tahun_ajaran', $tahunAjaran);
    }
    
    // Scope berdasarkan target
    public function scopeTarget($query, $target)
    {
        return $query->where('target', $target)
                     ->orWhere('target', 'semua');
    }
    
    // Accessor untuk mendapatkan durasi
    public function getDurasiAttribute()
    {
        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            $start = \Carbon\Carbon::parse($this->tanggal_mulai);
            $end = \Carbon\Carbon::parse($this->tanggal_selesai);
            return $end->diffInDays($start) + 1 . ' hari';
        }
        return '1 hari';
    }
    
    // Method untuk cek apakah event sedang berlangsung
    public function isOngoing()
    {
        $now = now();
        $start = \Carbon\Carbon::parse($this->tanggal_mulai);
        $end = $this->tanggal_selesai ? \Carbon\Carbon::parse($this->tanggal_selesai) : $start;
        
        return $now->between($start, $end);
    }
    
    // Method untuk cek event akan datang
    public function isUpcoming()
    {
        return \Carbon\Carbon::parse($this->tanggal_mulai)->isFuture();
    }
    
    // Method untuk cek event sudah selesai
    public function isPast()
    {
        $end = $this->tanggal_selesai ?: $this->tanggal_mulai;
        return \Carbon\Carbon::parse($end)->isPast();
    }
}