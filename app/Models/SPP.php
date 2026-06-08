<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// PASTIKAN IMPORT MODEL SISWA
use App\Models\Siswa;

class Spp extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'spp';

    protected $fillable = [
        'siswa_id',
        'bulan',
        'tahun',
        'jumlah',
        'status',
        'metode_bayar',
        'no_transaksi',
        'tanggal_bayar',
        'tanggal_jatuh_tempo',
        'keterangan',
        'user_id'
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'jumlah' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Constants untuk status
    const STATUS_BELUM_BAYAR = 'belum_bayar';
    const STATUS_LUNAS = 'lunas';
    const STATUS_TERLAMBAT = 'terlambat';

    // ==================== RELATIONS ====================
    
    // Relasi ke Siswa - PERBAIKI: gunakan foreign key yang benar
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    // Relasi ke User (petugas yang mencatat)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ==================== ACCESSORS ====================
    
    // Accessor untuk mendapatkan jumlah
    public function getJumlahValueAttribute()
    {
        return $this->jumlah ?? 0;
    }
    
    // Accessor untuk nama bulan
    public function getNamaBulanAttribute()
    {
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $bulanList[$this->bulan] ?? '-';
    }
    
    // Accessor untuk formatted jumlah
    public function getFormattedJumlahAttribute()
    {
        return 'Rp ' . number_format($this->jumlah ?? 0, 0, ',', '.');
    }
    
    // Accessor untuk formatted tanggal bayar
    public function getFormattedTanggalBayarAttribute()
    {
        return $this->tanggal_bayar ? $this->tanggal_bayar->format('d/m/Y') : '-';
    }

    // ==================== SCOPES ====================
    
    // Scope untuk status lunas
    public function scopeLunas($query)
    {
        return $query->where('status', self::STATUS_LUNAS);
    }

    // Scope untuk status belum bayar
    public function scopeBelumBayar($query)
    {
        return $query->where('status', self::STATUS_BELUM_BAYAR);
    }
    
    // Scope untuk status terlambat
    public function scopeTerlambat($query)
    {
        return $query->where('status', self::STATUS_TERLAMBAT);
    }
    
    // Scope berdasarkan bulan
    public function scopeBulan($query, $bulan)
    {
        return $query->where('bulan', $bulan);
    }
    
    // Scope berdasarkan tahun
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }
    
    // Scope berdasarkan siswa
    public function scopeSiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }
    
    // Scope untuk periode tertentu
    public function scopePeriode($query, $bulan, $tahun)
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }
    
    // Scope untuk yang sudah bayar (lunas) di bulan ini
    public function scopeLunasBulanIni($query)
    {
        return $query->where('status', self::STATUS_LUNAS)
            ->where('bulan', now()->month)
            ->where('tahun', now()->year);
    }

    // ==================== METHODS ====================
    
    // Method untuk menandai sebagai lunas
    public function markAsLunas()
    {
        $this->update([
            'status' => self::STATUS_LUNAS,
            'tanggal_bayar' => now()
        ]);
    }
    
    // Method untuk menandai sebagai terlambat
    public function markAsTerlambat()
    {
        $this->update(['status' => self::STATUS_TERLAMBAT]);
    }
    
    // Method untuk cek apakah lunas
    public function isLunas()
    {
        return $this->status == self::STATUS_LUNAS;
    }
    
    // Method untuk cek apakah terlambat
    public function isTerlambat()
    {
        return $this->status == self::STATUS_TERLAMBAT;
    }
    
    // Method untuk mendapatkan status dalam bahasa Indonesia
    public function getStatusText()
    {
        if ($this->status == self::STATUS_LUNAS) {
            return 'Lunas';
        } elseif ($this->status == self::STATUS_TERLAMBAT) {
            return 'Terlambat';
        }
        return 'Belum Bayar';
    }
}