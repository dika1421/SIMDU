<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'kelas';

    // FIX: Karena di DB kamu kolomnya create_at & update_at (tanpa d)
    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';
    
    protected $fillable = [
        'nama_kelas',
        'kode_kelas',
        'jurusan_id',
        'wali_kelas_id',
        'tingkat',
        'kapasitas',
        'tahun_ajaran',
        'status',
        'keterangan'
    ];
    
    protected $casts = [
        'kapasitas' => 'integer',
        'create_at' => 'datetime',
        'update_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
    
    // ==================== RELATIONS ====================
    
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }
    
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }
    
    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }
    
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'kelas_id');
    }
    
    public function jadwals()
    {
        return $this->hasMany(Jadwals::class, 'kelas_id', 'id');
    }
    
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'kelas_id');
    }
    
    public function spp()
    {
        return $this->hasManyThrough(Spp::class, Siswa::class, 'kelas_id', 'siswa_id');
    }
    
    // ==================== ACCESSORS ====================
    
    // FIX: jangan pakai $this->nama lagi biar gak loop
    public function getNamaKelasAttribute()
    {
        return $this->attributes['nama_kelas'] ?? $this->attributes['nama'] ?? '-';
    }
    
    // Alias biar kalau ada code lama pakai ->nama tetap jalan
    public function getNamaAttribute()
    {
        return $this->attributes['nama_kelas'] ?? $this->attributes['nama'] ?? '-';
    }

    public function getNamaLengkapAttribute()
    {
        $nama = $this->attributes['nama_kelas'] ?? '-';
        if ($this->jurusan) {
            $nama .= ' ' . ($this->jurusan->nama_jurusan ?? $this->jurusan->nama);
        }
        if ($this->tingkat) {
            $nama = $this->tingkat . ' ' . $nama;
        }
        return trim($nama);
    }
    
    public function getDisplayNameAttribute()
    {
        $name = $this->attributes['nama_kelas'] ?? '-';
        if ($this->tingkat) {
            $name = $this->tingkat . ' ' . $name;
        }
        if ($this->jurusan) {
            $name .= ' (' . ($this->jurusan->nama_jurusan ?? $this->jurusan->nama) . ')';
        }
        return $name;
    }
    
    public function getStatusTextAttribute()
    {
        if ($this->status == 'aktif') {
            return '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Aktif</span>';
        }
        return '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Nonaktif</span>';
    }
    
    public function getStatusLabelAttribute()
    {
        return $this->status == 'aktif' ? 'Aktif' : 'Nonaktif';
    }
    
    public function getJumlahSiswaAttribute()
    {
        return $this->siswa()->count();
    }
    
    public function getJumlahSiswaLakiAttribute()
    {
        return $this->siswa()->where('jenis_kelamin', 'L')->count();
    }
    
    public function getJumlahSiswaPerempuanAttribute()
    {
        return $this->siswa()->where('jenis_kelamin', 'P')->count();
    }
    
    public function getJumlahSiswaAktifAttribute()
    {
        return $this->siswa()->where('status', 'aktif')->count();
    }
    
    public function getSisaKapasitasAttribute()
    {
        return max(0, $this->kapasitas - $this->jumlah_siswa);
    }
    
    public function getPersentaseKapasitasAttribute()
    {
        if ($this->kapasitas && $this->kapasitas > 0) {
            return round(($this->jumlah_siswa / $this->kapasitas) * 100, 2);
        }
        return 0;
    }
    
    public function getKapasitasBarAttribute()
    {
        $persen = $this->persentase_kapasitas;
        $color = $persen >= 90 ? 'danger' : ($persen >= 75 ? 'warning' : 'success');
        
        return '<div class="progress" style="height: 20px;">
                    <div class="progress-bar bg-' . $color . '" role="progressbar" 
                         style="width: ' . $persen . '%;" 
                         aria-valuenow="' . $persen . '" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        ' . number_format($this->jumlah_siswa) . '/' . number_format($this->kapasitas) . '
                    </div>
                </div>';
    }
    
    public function getWaliKelasNameAttribute()
    {
        if ($this->waliKelas) {
            return $this->waliKelas->user->name ?? $this->waliKelas->nama_lengkap;
        }
        return '-';
    }
    
    public function getJurusanNameAttribute()
    {
        if ($this->jurusan) {
            return $this->jurusan->nama_jurusan ?? $this->jurusan->nama;
        }
        return '-';
    }
    
    // ==================== SCOPES ====================
    
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
    
    public function scopeInactive($query)
    {
        return $query->where('status', 'nonaktif');
    }
    
    public function scopeByTingkat($query, $tingkat)
    {
        if ($tingkat) {
            return $query->where('tingkat', $tingkat);
        }
        return $query;
    }
    
    public function scopeByJurusan($query, $jurusanId)
    {
        if ($jurusanId) {
            return $query->where('jurusan_id', $jurusanId);
        }
        return $query;
    }
    
    public function scopeByTahunAjaran($query, $tahunAjaran)
    {
        if ($tahunAjaran) {
            return $query->where('tahun_ajaran', $tahunAjaran);
        }
        return $query;
    }
    
    public function scopeNotFull($query)
    {
        return $query->whereRaw('(SELECT COUNT(*) FROM siswa WHERE siswa.kelas_id = kelas.id) < kelas.kapasitas');
    }
    
    public function scopeIsFull($query)
    {
        return $query->whereRaw('(SELECT COUNT(*) FROM siswa WHERE siswa.kelas_id = kelas.id) >= kelas.kapasitas');
    }
    
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('nama_kelas', 'LIKE', "%{$search}%")
                  ->orWhere('kode_kelas', 'LIKE', "%{$search}%")
                  ->orWhere('tingkat', 'LIKE', "%{$search}%");
            });
        }
        return $query;
    }
    
    // ==================== METHODS ====================
    
    public function isFull()
    {
        return $this->jumlah_siswa >= $this->kapasitas;
    }
    
    public function isAvailable()
    {
        return !$this->isFull();
    }
    
    public function canAcceptSiswa()
    {
        return $this->status == 'aktif' && $this->isAvailable();
    }
    
    public function getSiswaList()
    {
        return $this->siswa()->with('user')->orderBy('nama_lengkap')->get();
    }
    
    public function getJadwalByHari($hari = null)
    {
        $query = $this->jadwal()->with(['guru.user', 'mataPelajaran']);
        if ($hari) {
            $query->where('hari', $hari);
        }
        return $query->orderBy('jam_mulai')->get();
    }
    
    public function toSelect2Array()
    {
        return [
            'id' => $this->id,
            'text' => $this->display_name,
            'tingkat' => $this->tingkat,
            'jurusan' => $this->jurusan_name,
            'kapasitas' => $this->kapasitas,
            'jumlah_siswa' => $this->jumlah_siswa
        ];
    }
}