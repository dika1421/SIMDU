<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'kelas';
    
    protected $fillable = [
        'nama',
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
    
    // ==================== RELATIONS ====================
    
    /**
     * Relasi ke siswa
     */
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }
    
    /**
     * Relasi ke jurusan
     */
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }
    
    /**
     * Relasi ke wali kelas (guru)
     */
    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }
    
    /**
     * Relasi ke jadwal (tabel jadwal yang lama)
     */
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'kelas_id');
    }
    
    /**
     * TAMBAHKAN RELASI INI - Relasi ke jadwals (tabel jadwal yang digunakan)
     */
    public function jadwals()
    {
        return $this->hasMany(Jadwals::class, 'kelas_id', 'id');
    }
    
    /**
     * Relasi ke nilai
     */
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'kelas_id');
    }
    
    /**
     * Relasi ke spp (pembayaran SPP)
     */
    public function spp()
    {
        return $this->hasManyThrough(Spp::class, Siswa::class, 'kelas_id', 'siswa_id');
    }
    
    // ==================== ACCESSORS ====================
    
    /**
     * Accessor untuk nama_kelas (mengambil dari field nama atau nama_kelas)
     */
    public function getNamaKelasAttribute()
    {
        return $this->attributes['nama_kelas'] ?? $this->attributes['nama'] ?? $this->nama;
    }
    
    /**
     * Accessor untuk nama lengkap kelas (dengan jurusan)
     */
    public function getNamaLengkapAttribute()
    {
        $nama = $this->nama_kelas;
        if ($this->jurusan) {
            $nama .= ' ' . ($this->jurusan->nama_jurusan ?? $this->jurusan->nama);
        }
        if ($this->tingkat) {
            $nama = $this->tingkat . ' ' . $nama;
        }
        return trim($nama);
    }
    
    /**
     * Accessor untuk display nama (untuk dropdown)
     */
    public function getDisplayNameAttribute()
    {
        $name = $this->nama_kelas;
        if ($this->tingkat) {
            $name = $this->tingkat . ' ' . $name;
        }
        if ($this->jurusan) {
            $name .= ' (' . ($this->jurusan->nama_jurusan ?? $this->jurusan->nama) . ')';
        }
        return $name;
    }
    
    /**
     * Accessor untuk status text (HTML badge)
     */
    public function getStatusTextAttribute()
    {
        if ($this->status == 'aktif') {
            return '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Aktif</span>';
        }
        return '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Nonaktif</span>';
    }
    
    /**
     * Accessor untuk status plain text
     */
    public function getStatusLabelAttribute()
    {
        return $this->status == 'aktif' ? 'Aktif' : 'Nonaktif';
    }
    
    /**
     * Accessor untuk jumlah siswa
     */
    public function getJumlahSiswaAttribute()
    {
        return $this->siswa()->count();
    }
    
    /**
     * Accessor untuk jumlah siswa laki-laki
     */
    public function getJumlahSiswaLakiAttribute()
    {
        return $this->siswa()->where('jenis_kelamin', 'L')->count();
    }
    
    /**
     * Accessor untuk jumlah siswa perempuan
     */
    public function getJumlahSiswaPerempuanAttribute()
    {
        return $this->siswa()->where('jenis_kelamin', 'P')->count();
    }
    
    /**
     * Accessor untuk jumlah siswa aktif
     */
    public function getJumlahSiswaAktifAttribute()
    {
        return $this->siswa()->where('status', 'aktif')->count();
    }
    
    /**
     * Accessor untuk sisa kapasitas
     */
    public function getSisaKapasitasAttribute()
    {
        return max(0, $this->kapasitas - $this->jumlah_siswa);
    }
    
    /**
     * Accessor untuk persentase kapasitas
     */
    public function getPersentaseKapasitasAttribute()
    {
        if ($this->kapasitas && $this->kapasitas > 0) {
            return round(($this->jumlah_siswa / $this->kapasitas) * 100, 2);
        }
        return 0;
    }
    
    /**
     * Accessor untuk status kapasitas (HTML progress bar)
     */
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
    
    /**
     * Accessor untuk wali kelas name
     */
    public function getWaliKelasNameAttribute()
    {
        if ($this->waliKelas) {
            return $this->waliKelas->user->name ?? $this->waliKelas->nama_lengkap;
        }
        return '-';
    }
    
    /**
     * Accessor untuk jurusan name
     */
    public function getJurusanNameAttribute()
    {
        if ($this->jurusan) {
            return $this->jurusan->nama_jurusan ?? $this->jurusan->nama;
        }
        return '-';
    }
    
    // ==================== SCOPES ====================
    
    /**
     * Scope untuk kelas aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
    
    /**
     * Scope untuk kelas nonaktif
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'nonaktif');
    }
    
    /**
     * Scope berdasarkan tingkat
     */
    public function scopeByTingkat($query, $tingkat)
    {
        if ($tingkat) {
            return $query->where('tingkat', $tingkat);
        }
        return $query;
    }
    
    /**
     * Scope berdasarkan jurusan
     */
    public function scopeByJurusan($query, $jurusanId)
    {
        if ($jurusanId) {
            return $query->where('jurusan_id', $jurusanId);
        }
        return $query;
    }
    
    /**
     * Scope berdasarkan tahun ajaran
     */
    public function scopeByTahunAjaran($query, $tahunAjaran)
    {
        if ($tahunAjaran) {
            return $query->where('tahun_ajaran', $tahunAjaran);
        }
        return $query;
    }
    
    /**
     * Scope untuk kelas yang tidak penuh
     */
    public function scopeNotFull($query)
    {
        return $query->whereRaw('(SELECT COUNT(*) FROM siswa WHERE siswa.kelas_id = kelas.id) < kelas.kapasitas');
    }
    
    /**
     * Scope untuk kelas yang penuh
     */
    public function scopeIsFull($query)
    {
        return $query->whereRaw('(SELECT COUNT(*) FROM siswa WHERE siswa.kelas_id = kelas.id) >= kelas.kapasitas');
    }
    
    /**
     * Scope pencarian
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nama_kelas', 'LIKE', "%{$search}%")
                  ->orWhere('kode_kelas', 'LIKE', "%{$search}%")
                  ->orWhere('tingkat', 'LIKE', "%{$search}%");
            });
        }
        return $query;
    }
    
    // ==================== METHODS ====================
    
    /**
     * Cek apakah kelas penuh
     */
    public function isFull()
    {
        return $this->jumlah_siswa >= $this->kapasitas;
    }
    
    /**
     * Cek apakah kelas tersedia (belum penuh)
     */
    public function isAvailable()
    {
        return !$this->isFull();
    }
    
    /**
     * Cek apakah kelas bisa menerima siswa baru
     */
    public function canAcceptSiswa()
    {
        return $this->status == 'aktif' && $this->isAvailable();
    }
    
    /**
     * Mendapatkan daftar siswa per kelas
     */
    public function getSiswaList()
    {
        return $this->siswa()->with('user')->orderBy('nama_lengkap')->get();
    }
    
    /**
     * Mendapatkan daftar jadwal per kelas
     */
    public function getJadwalByHari($hari = null)
    {
        $query = $this->jadwal()->with(['guru.user', 'mataPelajaran']);
        
        if ($hari) {
            $query->where('hari', $hari);
        }
        
        return $query->orderBy('jam_mulai')->get();
    }
    
    /**
     * Format untuk dropdown select2
     */
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