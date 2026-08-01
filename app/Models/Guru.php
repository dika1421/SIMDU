<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Guru extends Model
{
    use SoftDeletes;
    
    protected $table = 'gurus';
    
    protected $fillable = [
        'user_id',
        'nip',
        'rfid',
        'nuptk',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telepon',
        'pendidikan_terakhir',
        'jurusan_pendidikan',
        'universitas',
        'tahun_lulus',
        'tmt_masuk',
        'status_kepegawaian',
        'golongan',
        'mata_pelajaran_utama',
        'keahlian_khusus',
        'status',
        'jabatan_id',
    ];
    
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tmt_masuk' => 'date',
        'tahun_lulus' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    
    // ==================== RELATIONS ====================
    
    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Relasi ke Jabatan
     */
    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
    
    /**
     * 🔥 Relasi ke Jadwal (HANYA SATU)
     * Guru memiliki banyak jadwal mengajar
     */
    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'guru_id');
    }
    
    /**
     * Relasi ke Nilai
     */
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'guru_id');
    }
    
    /**
     * Relasi ke Kelas (sebagai wali kelas)
     */
    public function kelasWali(): HasMany
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id', 'id');
    }
    
    /**
     * Relasi ke Absensi (menggunakan guru_id)
     */
    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class, 'guru_id')->where('absensi_type', 'guru');
    }
    
    /**
     * Relasi ke Mapel melalui tabel jadwal (guru mengajar mapel)
     */
    public function mataPelajaran(): BelongsToMany
    {
        return $this->belongsToMany(Mapel::class, 'jadwal', 'guru_id', 'mapel_id')
                    ->withPivot('kelas_id', 'hari', 'jam_mulai', 'jam_selesai')
                    ->withTimestamps();
    }
    
    /**
     * Alias untuk mataPelajaran (untuk kemudahan)
     */
    public function mapel(): BelongsToMany
    {
        return $this->mataPelajaran();
    }
    
    // ==================== ACCESSORS ====================
    
    /**
     * Get daftar mata pelajaran yang diajar
     */
    public function getMataPelajaranListAttribute(): string
    {
        return $this->mataPelajaran->pluck('nama_mapel')->implode(', ');
    }
    
    /**
     * Get jumlah mata pelajaran yang diajar
     */
    public function getMataPelajaranCountAttribute(): int
    {
        return $this->mataPelajaran->count();
    }
    
    /**
     * Get nama lengkap
     */
    public function getNameAttribute(): string
    {
        return $this->nama_lengkap;
    }
    
    /**
     * Get nama jabatan
     */
    public function getNamaJabatanAttribute(): string
    {
        if ($this->jabatan) {
            return $this->jabatan->nama_jabatan;
        }
        return $this->status_kepegawaian ?? '-';
    }
    
    /**
     * Get umur
     */
    public function getUmurAttribute(): ?int
    {
        if ($this->tanggal_lahir) {
            return $this->tanggal_lahir->age;
        }
        return null;
    }
    
    /**
     * Get status text dengan badge
     */
    public function getStatusTextAttribute(): string
    {
        if ($this->status == 'aktif') {
            return '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Aktif</span>';
        }
        return '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Nonaktif</span>';
    }
    
    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status == 'aktif' ? 'Aktif' : 'Nonaktif';
    }
    
    /**
     * Get jenis kelamin label
     */
    public function getJenisKelaminLabelAttribute(): string
    {
        if ($this->jenis_kelamin == 'L') {
            return 'Laki-laki';
        }
        return 'Perempuan';
    }
    
    // ==================== SCOPES ====================
    
    /**
     * Scope untuk guru aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
    
    /**
     * Scope untuk guru nonaktif
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'nonaktif');
    }
    
    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('nip', 'LIKE', "%{$search}%")
                  ->orWhere('nuptk', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($user) use ($search) {
                      $user->where('name', 'LIKE', "%{$search}%")
                           ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }
        return $query;
    }
    
    /**
     * Scope untuk guru berdasarkan jabatan
     */
    public function scopeByJabatan($query, $jabatanId)
    {
        if ($jabatanId) {
            return $query->where('jabatan_id', $jabatanId);
        }
        return $query;
    }
    
    /**
     * Scope untuk guru berdasarkan mata pelajaran
     */
    public function scopeByMataPelajaran($query, $mapelId)
    {
        if ($mapelId) {
            return $query->whereHas('mataPelajaran', function($q) use ($mapelId) {
                $q->where('mapel_id', $mapelId);
            });
        }
        return $query;
    }
    
    // ==================== METHODS ====================
    
    /**
     * Cek apakah guru aktif
     */
    public function isActive(): bool
    {
        return $this->status == 'aktif';
    }
    
    /**
     * Cek apakah guru adalah wali kelas
     */
    public function isWaliKelas(): bool
    {
        return $this->kelasWali()->count() > 0;
    }
    
    /**
     * Get kelas yang diampu sebagai wali kelas
     */
    public function getKelasWali()
    {
        return $this->kelasWali()->first();
    }
    
    /**
     * Get jadwal mengajar berdasarkan hari
     */
    public function getJadwalByHari($hari = null)
    {
        $query = $this->jadwal()->with(['kelas', 'mataPelajaran']);
        if ($hari) {
            $query->where('hari', $hari);
        }
        return $query->orderBy('jam_mulai')->get();
    }
    
    /**
     * Get total jam mengajar per minggu
     */
    public function getTotalJamMengajarAttribute(): int
    {
        return $this->jadwal()->count();
    }
}