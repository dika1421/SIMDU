<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jurusan extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'jurusan';
    
    protected $fillable = [
        'kode_jurusan',
        'nama',
        'deskripsi',
        'kepala_jurusan_id',
        'status'
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
    
    // ==================== ACCESSORS ====================
    
    /**
     * Accessor untuk kode_jurusan
     */
    public function getKodeJurusanAttribute()
    {
        return $this->attributes['kode_jurusan'] ?? 'JRS-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }
    
    /**
     * Accessor untuk nama_jurusan (agar kompatibel dengan controller)
     */
    public function getNamaJurusanAttribute()
    {
        return $this->nama;
    }
    
    /**
     * Accessor untuk display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->nama;
    }
    
    /**
     * Accessor untuk status text (HTML badge)
     */
    public function getStatusTextAttribute()
    {
        if ($this->status == 'aktif') {
            return '<span class="badge bg-success">Aktif</span>';
        }
        return '<span class="badge bg-danger">Nonaktif</span>';
    }
    
    /**
     * Accessor untuk status plain text
     */
    public function getStatusLabelAttribute()
    {
        return $this->status == 'aktif' ? 'Aktif' : 'Non Aktif';
    }
    
    // ==================== MUTATORS ====================
    
    /**
     * Mutator untuk nama - otomatis uppercase first letter
     */
    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = ucwords(strtolower(trim($value)));
    }
    
    /**
     * Mutator untuk kode_jurusan - otomatis uppercase
     */
    public function setKodeJurusanAttribute($value)
    {
        $this->attributes['kode_jurusan'] = strtoupper(trim($value));
    }
    
    // ==================== RELATIONS ====================
    
    /**
     * Relasi ke kelas
     */
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'jurusan_id');
    }
    
    /**
     * Relasi ke siswa melalui kelas
     */
    public function siswa()
    {
        return $this->hasManyThrough(
            Siswa::class,
            Kelas::class,
            'jurusan_id',
            'kelas_id',
            'id',
            'id'
        );
    }
    
    /**
     * Relasi ke guru sebagai kepala jurusan
     */
    public function kepalaJurusan()
    {
        return $this->belongsTo(Guru::class, 'kepala_jurusan_id', 'id');
    }
    
    // ==================== SCOPES ====================
    
    /**
     * Scope untuk jurusan aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
    
    /**
     * Scope untuk jurusan nonaktif
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'nonaktif');
    }
    
    /**
     * Scope pencarian
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('kode_jurusan', 'LIKE', "%{$search}%");
            });
        }
        return $query;
    }
    
    /**
     * Scope untuk sorting berdasarkan nama
     */
    public function scopeOrderByNama($query, $direction = 'asc')
    {
        return $query->orderBy('nama', $direction);
    }
    
    // ==================== HELPER METHODS ====================
    
    /**
     * Cek apakah jurusan aktif
     */
    public function isActive()
    {
        return $this->status === 'aktif';
    }
    
    /**
     * Hitung jumlah kelas dalam jurusan ini
     */
    public function getJumlahKelasAttribute()
    {
        return $this->kelas()->count();
    }
    
    /**
     * Hitung jumlah siswa dalam jurusan ini
     */
    public function getJumlahSiswaAttribute()
    {
        return $this->siswa()->count();
    }
    
    /**
     * Generate kode jurusan otomatis dari nama
     */
    public static function generateKodeFromNama($nama)
    {
        // Ambil 3 huruf pertama dari nama
        $kode = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $nama), 0, 3));
        
        // Cek apakah kode sudah ada
        $exists = self::where('kode_jurusan', $kode)->exists();
        if ($exists) {
            $counter = 1;
            while (self::where('kode_jurusan', $kode . $counter)->exists()) {
                $counter++;
            }
            $kode = $kode . $counter;
        }
        
        return $kode;
    }
    
    /**
     * Generate kode jurusan otomatis berdasarkan urutan
     */
    public static function generateKodeJurusan()
    {
        $lastJurusan = self::withTrashed()->orderBy('id', 'desc')->first();
        $lastNumber = $lastJurusan ? intval(substr($lastJurusan->kode_jurusan, -3)) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        
        return 'JRS-' . $newNumber;
    }
    
    /**
     * Get data untuk dropdown (key-value pair)
     */
    public static function getForDropdown($includeNonaktif = false)
    {
        $query = self::orderBy('nama');
        
        if (!$includeNonaktif) {
            $query->active();
        }
        
        return $query->pluck('nama', 'id');
    }
    
    /**
     * Get data untuk select2
     */
    public static function getForSelect2($search = null)
    {
        $query = self::active()->orderBy('nama');
        
        if ($search) {
            $query->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('kode_jurusan', 'LIKE', "%{$search}%");
        }
        
        return $query->get(['id', 'kode_jurusan', 'nama'])->map(function($item) {
            return [
                'id' => $item->id,
                'text' => $item->kode_jurusan . ' - ' . $item->nama
            ];
        });
    }
    
    // ==================== BOOT TRAITS ====================
    
    protected static function boot()
    {
        parent::boot();
        
        // Auto-generate kode_jurusan sebelum create jika kosong
        static::creating(function ($jurusan) {
            if (empty($jurusan->kode_jurusan)) {
                $jurusan->kode_jurusan = self::generateKodeFromNama($jurusan->nama);
            }
        });
    }
}