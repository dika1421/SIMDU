<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArsipDokumen extends Model
{
    use SoftDeletes;
    
    protected $table = 'arsip_dokumen';
    
    protected $fillable = [
        'kode_arsip',
        'judul',
        'jenis_dokumen',
        'deskripsi',
        'nama_file',
        'path_file',
        'tipe_file',
        'ukuran_file',
        'uploaded_by',
        'siswa_id',
        'guru_id',
        'kelas_id',
        'status',
        'kategori',
        'tahun',
        'tanggal_dokumen'
    ];
    
    protected $casts = [
        'tanggal_dokumen' => 'date',
        'ukuran_file' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
    
    // Relasi
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
    
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
    
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    
    // Scope
    public function scopeKategori($query, $kategori)
    {
        if ($kategori) {
            return $query->where('kategori', $kategori);
        }
        return $query;
    }
    
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                  ->orWhere('kode_arsip', 'LIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$search}%");
            });
        }
        return $query;
    }
    
    public function scopeTahun($query, $tahun)
    {
        if ($tahun) {
            return $query->where('tahun', $tahun);
        }
        return $query;
    }
    
    // Accessor
    public function getKategoriBadgeAttribute()
    {
        $badges = [
            'surat_keputusan' => 'primary',
            'laporan_bulanan' => 'info',
            'sertifikat' => 'success',
            'dokumen_siswa' => 'warning',
            'dokumen_guru' => 'danger',
            'akreditasi' => 'secondary',
            'kurikulum' => 'dark',
            'keuangan' => 'success'
        ];
        
        $labels = [
            'surat_keputusan' => 'Surat Keputusan',
            'laporan_bulanan' => 'Laporan Bulanan',
            'sertifikat' => 'Sertifikat',
            'dokumen_siswa' => 'Dokumen Siswa',
            'dokumen_guru' => 'Dokumen Guru',
            'akreditasi' => 'Akreditasi',
            'kurikulum' => 'Kurikulum',
            'keuangan' => 'Keuangan'
        ];
        
        $color = $badges[$this->kategori] ?? 'secondary';
        $label = $labels[$this->kategori] ?? $this->kategori;
        
        return "<span class='badge bg-{$color}'>{$label}</span>";
    }
    
    public function getUkuranFileFormattedAttribute()
    {
        $bytes = $this->ukuran_file;
        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' B';
        }
    }
    
    public function getStatusBadgeAttribute()
    {
        if ($this->status === 'aktif') {
            return '<span class="badge bg-success">Aktif</span>';
        }
        return '<span class="badge bg-danger">Tidak Aktif</span>';
    }
}