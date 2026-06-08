<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keuangan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'keuangan';

    protected $fillable = [
        'no_transaksi',
        'tanggal',
        'tipe',
        'kategori',
        'jumlah',
        'keterangan',
        'siswa_id',
        'user_id',
        'metode_bayar'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // ==================== RELATIONS ====================
    
    /**
     * Relasi ke siswa (untuk pembayaran siswa)
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Relasi ke user yang mencatat transaksi
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ==================== SCOPES ====================
    
    /**
     * Scope untuk filter pemasukan
     */
    public function scopePemasukan($query)
    {
        return $query->where('tipe', 'pemasukan');
    }

    /**
     * Scope untuk filter pengeluaran
     */
    public function scopePengeluaran($query)
    {
        return $query->where('tipe', 'pengeluaran');
    }

    /**
     * Scope untuk filter berdasarkan bulan
     */
    public function scopeBulan($query, $bulan, $tahun = null)
    {
        $tahun = $tahun ?? now()->year;
        return $query->whereMonth('tanggal', $bulan)
                     ->whereYear('tanggal', $tahun);
    }

    /**
     * Scope untuk filter berdasarkan rentang tanggal
     */
    public function scopeRentangTanggal($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope untuk filter berdasarkan metode pembayaran
     */
    public function scopeMetodeBayar($query, $metode)
    {
        return $query->where('metode_bayar', $metode);
    }

    // ==================== ACCESSORS ====================
    
    /**
     * Get formatted jumlah
     */
    public function getFormattedJumlahAttribute()
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    /**
     * Get formatted tanggal
     */
    public function getFormattedTanggalAttribute()
    {
        return $this->tanggal ? $this->tanggal->format('d/m/Y') : '-';
    }

    /**
     * Get tipe with badge style
     */
    public function getTipeBadgeAttribute()
    {
        return $this->tipe == 'pemasukan' 
            ? '<span class="badge bg-success">Pemasukan</span>' 
            : '<span class="badge bg-danger">Pengeluaran</span>';
    }

    // ==================== HELPERS ====================
    
    /**
     * Generate nomor transaksi unik
     */
    public static function generateNoTransaksi($prefix = 'TRX')
    {
        $date = date('Ymd');
        $lastId = self::whereDate('created_at', today())->count() + 1;
        return $prefix . '-' . $date . '-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get total pemasukan per bulan
     */
    public static function getTotalPemasukanBulanIni()
    {
        return self::pemasukan()
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('jumlah');
    }

    /**
     * Get total pengeluaran per bulan
     */
    public static function getTotalPengeluaranBulanIni()
    {
        return self::pengeluaran()
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('jumlah');
    }

    /**
     * Get saldo bulan ini
     */
    public static function getSaldoBulanIni()
    {
        return self::getTotalPemasukanBulanIni() - self::getTotalPengeluaranBulanIni();
    }
}