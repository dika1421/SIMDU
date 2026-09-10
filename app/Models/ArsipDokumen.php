<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArsipDokumen extends Model
{
    use SoftDeletes;

    protected $table = 'arsip_dokumen';

    protected $fillable = [
        'nomor_dokumen',
        'nama_dokumen',
        'kategori',
        'jenis_dokumen',
        'tanggal_dokumen',
        'file_path',
        'keterangan',
        'uploaded_by',
        'tahun',
    ];

    protected $casts = [
        'tanggal_dokumen' => 'date',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
        'deleted_at'      => 'datetime',
    ];

    /* ================= RELASI ================= */

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /* ================= SCOPE ================= */

    public function scopeKategori($query, $kategori)
    {
        return $kategori ? $query->where('kategori', $kategori) : $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('nama_dokumen', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_dokumen', 'LIKE', "%{$search}%")
                  ->orWhere('keterangan', 'LIKE', "%{$search}%");
            });
        }
        return $query;
    }

    public function scopeTahun($query, $tahun)
    {
        return $tahun ? $query->where('tahun', $tahun) : $query;
    }

    /* ================= ACCESSOR ================= */

    public function getKategoriBadgeAttribute()
    {
        $badges = [
            'surat_keputusan' => 'primary',
            'laporan_bulanan' => 'info',
            'sertifikat'      => 'success',
            'dokumen_siswa'   => 'warning',
            'dokumen_guru'    => 'danger',
            'akreditasi'      => 'secondary',
            'kurikulum'       => 'dark',
            'keuangan'        => 'success',
        ];

        $labels = [
            'surat_keputusan' => 'Surat Keputusan',
            'laporan_bulanan' => 'Laporan Bulanan',
            'sertifikat'      => 'Sertifikat',
            'dokumen_siswa'   => 'Dokumen Siswa',
            'dokumen_guru'    => 'Dokumen Guru',
            'akreditasi'      => 'Akreditasi',
            'kurikulum'       => 'Kurikulum',
            'keuangan'        => 'Keuangan',
        ];

        $color = $badges[$this->kategori] ?? 'secondary';
        $label = $labels[$this->kategori] ?? $this->kategori;

        return "<span class='badge bg-{$color}'>{$label}</span>";
    }

    public function getStatusBadgeAttribute()
    {
        // Karena tabel tidak punya kolom status, selalu "Aktif"
        return '<span class="badge bg-success">Aktif</span>';
    }

    /**
     * Nama file asli dari path (untuk ditampilkan).
     */
    public function getNamaFileAttribute()
    {
        return $this->file_path ? basename($this->file_path) : '-';
    }

    /**
     * Ukuran file (jika kolom tidak ada, return null).
     */
    public function getUkuranFileFormattedAttribute()
    {
        $path = $this->file_path;
        if (!$path) return '-';

        try {
            $fullPath = storage_path('app/public/' . $path);
            if (file_exists($fullPath)) {
                $bytes = filesize($fullPath);
                if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
                if ($bytes >= 1024)    return round($bytes / 1024, 2) . ' KB';
                return $bytes . ' B';
            }
        } catch (\Exception $e) {
            return '-';
        }

        return '-';
    }
}