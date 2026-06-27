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
        'jenis_pembayaran',
        'jumlah',
        'tagihan',
        'terbayar',
        'sisa',
        'status_pembayaran',
        'keterangan',
        'siswa_id',
        'user_id',
        'metode_bayar',
        'tanggal_jatuh_tempo',
        'created_by'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
        'tagihan' => 'decimal:2',
        'terbayar' => 'decimal:2',
        'sisa' => 'decimal:2',
        'tanggal_jatuh_tempo' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // ==================== CONSTANTS ====================
    
    // Tipe transaksi
    const TIPE_PEMASUKAN = 'pemasukan';
    const TIPE_PENGELUARAN = 'pengeluaran';
    
    // Jenis pembayaran (untuk siswa)
    const JENIS_SPP = 'spp';
    const JENIS_UANG_BANGUNAN = 'uang_bangunan';
    const JENIS_UANG_KEGIATAN = 'uang_kegiatan';
    const JENIS_UANG_SERAGAM = 'uang_seragam';
    const JENIS_UANG_BUKU = 'uang_buku';
    const JENIS_LAINNYA = 'lainnya';
    
    // Status pembayaran
    const STATUS_LUNAS = 'lunas';
    const STATUS_BELUM_LUNAS = 'belum_lunas';
    const STATUS_TERLAMBAT = 'terlambat';
    const STATUS_ANGSURAN = 'angsuran';
    
    // Metode pembayaran
    const METODE_TUNAI = 'tunai';
    const METODE_TRANSFER = 'transfer';
    const METODE_KARTU = 'kartu';
    const METODE_QRIS = 'qris';

    // ==================== RELATIONS ====================
    
    /**
     * Relasi ke siswa (untuk pembayaran siswa)
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Relasi ke user yang mencatat transaksi (administrasi)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke user yang membuat data (created_by)
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ==================== SCOPES ====================
    
    /**
     * Scope untuk filter pemasukan
     */
    public function scopePemasukan($query)
    {
        return $query->where('tipe', self::TIPE_PEMASUKAN);
    }

    /**
     * Scope untuk filter pengeluaran
     */
    public function scopePengeluaran($query)
    {
        return $query->where('tipe', self::TIPE_PENGELUARAN);
    }

    /**
     * Scope untuk filter pembayaran siswa (yang memiliki siswa_id)
     */
    public function scopePembayaranSiswa($query)
    {
        return $query->whereNotNull('siswa_id')
                     ->where('tipe', self::TIPE_PEMASUKAN);
    }

    /**
     * Scope untuk filter berdasarkan siswa tertentu
     */
    public function scopeForSiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    /**
     * Scope untuk filter berdasarkan jenis pembayaran
     */
    public function scopeJenisPembayaran($query, $jenis)
    {
        return $query->where('jenis_pembayaran', $jenis);
    }

    /**
     * Scope untuk filter status pembayaran
     */
    public function scopeStatusPembayaran($query, $status)
    {
        return $query->where('status_pembayaran', $status);
    }

    /**
     * Scope untuk pembayaran yang belum lunas
     */
    public function scopeBelumLunas($query)
    {
        return $query->where('status_pembayaran', self::STATUS_BELUM_LUNAS)
                     ->orWhere('status_pembayaran', self::STATUS_ANGSURAN);
    }

    /**
     * Scope untuk pembayaran terlambat
     */
    public function scopeTerlambat($query)
    {
        return $query->where('status_pembayaran', self::STATUS_TERLAMBAT)
                     ->orWhere(function($q) {
                         $q->where('status_pembayaran', self::STATUS_BELUM_LUNAS)
                           ->where('tanggal_jatuh_tempo', '<', now());
                     });
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
     * Get formatted tagihan
     */
    public function getFormattedTagihanAttribute()
    {
        return 'Rp ' . number_format($this->tagihan ?? 0, 0, ',', '.');
    }

    /**
     * Get formatted terbayar
     */
    public function getFormattedTerbayarAttribute()
    {
        return 'Rp ' . number_format($this->terbayar ?? 0, 0, ',', '.');
    }

    /**
     * Get formatted sisa
     */
    public function getFormattedSisaAttribute()
    {
        return 'Rp ' . number_format($this->sisa ?? 0, 0, ',', '.');
    }

    /**
     * Get formatted tanggal
     */
    public function getFormattedTanggalAttribute()
    {
        return $this->tanggal ? $this->tanggal->format('d/m/Y') : '-';
    }

    /**
     * Get formatted tanggal jatuh tempo
     */
    public function getFormattedJatuhTempoAttribute()
    {
        return $this->tanggal_jatuh_tempo ? $this->tanggal_jatuh_tempo->format('d/m/Y') : '-';
    }

    /**
     * Get tipe badge
     */
    public function getTipeBadgeAttribute()
    {
        return $this->tipe == self::TIPE_PEMASUKAN 
            ? '<span class="badge bg-success">Pemasukan</span>' 
            : '<span class="badge bg-danger">Pengeluaran</span>';
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_LUNAS => 'success',
            self::STATUS_BELUM_LUNAS => 'danger',
            self::STATUS_TERLAMBAT => 'warning',
            self::STATUS_ANGSURAN => 'info'
        ];
        
        $color = $badges[$this->status_pembayaran] ?? 'secondary';
        $statusText = $this->getStatusLabel();
        
        return "<span class='badge bg-{$color} px-3 py-2'>{$statusText}</span>";
    }

    /**
     * Get jenis pembayaran label
     */
    public function getJenisPembayaranLabelAttribute()
    {
        return $this->getJenisPembayaranLabel();
    }

    /**
     * Get persentase pembayaran
     */
    public function getPersentaseAttribute()
    {
        if ($this->tagihan && $this->tagihan > 0) {
            return round(($this->terbayar / $this->tagihan) * 100, 2);
        }
        return 0;
    }

    /**
     * Get progress width for progress bar
     */
    public function getProgressWidthAttribute()
    {
        return min(100, $this->persentase);
    }

    // ==================== HELPERS ====================
    
    /**
     * Get jenis pembayaran label
     */
    public function getJenisPembayaranLabel()
    {
        $labels = self::getJenisPembayaranOptions();
        return $labels[$this->jenis_pembayaran] ?? ucfirst($this->jenis_pembayaran);
    }

    /**
     * Get status label
     */
    public function getStatusLabel()
    {
        $labels = self::getStatusOptions();
        return $labels[$this->status_pembayaran] ?? ucfirst($this->status_pembayaran);
    }

    /**
     * Get all jenis pembayaran options
     */
    public static function getJenisPembayaranOptions(): array
    {
        return [
            self::JENIS_SPP => 'SPP',
            self::JENIS_UANG_BANGUNAN => 'Uang Bangunan',
            self::JENIS_UANG_KEGIATAN => 'Uang Kegiatan',
            self::JENIS_UANG_SERAGAM => 'Uang Seragam',
            self::JENIS_UANG_BUKU => 'Uang Buku',
            self::JENIS_LAINNYA => 'Lainnya',
        ];
    }

    /**
     * Get all status options
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_LUNAS => 'Lunas',
            self::STATUS_BELUM_LUNAS => 'Belum Lunas',
            self::STATUS_TERLAMBAT => 'Terlambat',
            self::STATUS_ANGSURAN => 'Angsuran',
        ];
    }

    /**
     * Get all metode bayar options
     */
    public static function getMetodeBayarOptions(): array
    {
        return [
            self::METODE_TUNAI => 'Tunai',
            self::METODE_TRANSFER => 'Transfer Bank',
            self::METODE_KARTU => 'Kartu Kredit/Debit',
            self::METODE_QRIS => 'QRIS',
        ];
    }

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

    /**
     * Get total tagihan siswa tertentu
     */
    public static function getTotalTagihanSiswa($siswaId)
    {
        return self::where('siswa_id', $siswaId)
            ->sum('tagihan');
    }

    /**
     * Get total terbayar siswa tertentu
     */
    public static function getTotalTerbayarSiswa($siswaId)
    {
        return self::where('siswa_id', $siswaId)
            ->sum('terbayar');
    }

    /**
     * Get total sisa tagihan siswa tertentu
     */
    public static function getTotalSisaSiswa($siswaId)
    {
        return self::getTotalTagihanSiswa($siswaId) - self::getTotalTerbayarSiswa($siswaId);
    }

    /**
     * Update status pembayaran berdasarkan sisa tagihan
     */
    public function updateStatusPembayaran()
    {
        if ($this->sisa <= 0) {
            $this->status_pembayaran = self::STATUS_LUNAS;
        } elseif ($this->terbayar > 0 && $this->sisa > 0) {
            $this->status_pembayaran = self::STATUS_ANGSURAN;
        } else {
            // Cek apakah sudah melewati jatuh tempo
            if ($this->tanggal_jatuh_tempo && $this->tanggal_jatuh_tempo < now()) {
                $this->status_pembayaran = self::STATUS_TERLAMBAT;
            } else {
                $this->status_pembayaran = self::STATUS_BELUM_LUNAS;
            }
        }
        
        $this->saveQuietly();
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            // Auto generate no transaksi jika belum ada
            if (empty($model->no_transaksi)) {
                $model->no_transaksi = self::generateNoTransaksi();
            }
            
            // Set tipe ke pemasukan jika ada siswa_id
            if ($model->siswa_id && empty($model->tipe)) {
                $model->tipe = self::TIPE_PEMASUKAN;
            }
        });
        
        static::saving(function ($model) {
            // Hitung sisa tagihan
            if ($model->tagihan && $model->terbayar) {
                $model->sisa = $model->tagihan - $model->terbayar;
            }
        });
        
        static::saved(function ($model) {
            // Update status pembayaran setelah disimpan
            $model->updateStatusPembayaran();
        });
    }
}