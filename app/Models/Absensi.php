<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    
    protected $fillable = [
        // Menggunakan siswa_id langsung (tanpa absensi_type)
        'siswa_id',
        'guru_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'status',
        'keterangan',
        'diinput_oleh',
        'mata_pelajaran_id',
        'kelas_id',
        'jenis_absensi', // 'siswa' atau 'guru'
        'latitude',
        'longitude',
        'foto',
        'user_id', // untuk polymorphic support
        'user_type', // untuk polymorphic support
    ];

    protected $casts = [
        'tanggal' => 'date:Y-m-d',
        'jam_masuk' => 'datetime:H:i:s',
        'jam_keluar' => 'datetime:H:i:s',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Status constants
    const STATUS_HADIR = 'hadir';
    const STATUS_IZIN = 'izin';
    const STATUS_SAKIT = 'sakit';
    const STATUS_ALPHA = 'alpha';
    const STATUS_TERLAMBAT = 'terlambat';
    
    // Absensi type constants
    const TYPE_SISWA = 'siswa';
    const TYPE_GURU = 'guru';

    /**
     * Get all available status options
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_HADIR => 'Hadir',
            self::STATUS_IZIN => 'Izin',
            self::STATUS_SAKIT => 'Sakit',
            self::STATUS_ALPHA => 'Alpha',
            self::STATUS_TERLAMBAT => 'Terlambat',
        ];
    }

    /**
     * Get all available type options
     */
    public static function getTypeOptions(): array
    {
        return [
            self::TYPE_SISWA => 'Siswa',
            self::TYPE_GURU => 'Guru',
        ];
    }

    /**
     * Relasi ke model Siswa
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Relasi ke model Guru
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    /**
     * Relasi ke user yang menginput absensi
     */
    public function diinputOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diinput_oleh');
    }

    /**
     * Relasi ke mata pelajaran (untuk absensi per mata pelajaran)
     */
    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(Mapel::class, 'mata_pelajaran_id');
    }

    /**
     * Relasi ke kelas
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Relasi polymorphic ke User (Siswa atau Guru)
     */
    public function user()
    {
        return $this->morphTo();
    }

    /**
     * Scope untuk absensi siswa
     */
    public function scopeSiswa(Builder $query): Builder
    {
        return $query->where(function($q) {
            $q->where('jenis_absensi', self::TYPE_SISWA)
              ->orWhereNotNull('siswa_id');
        })->whereNotNull('siswa_id');
    }

    /**
     * Scope untuk absensi guru
     */
    public function scopeGuru(Builder $query): Builder
    {
        return $query->where(function($q) {
            $q->where('jenis_absensi', self::TYPE_GURU)
              ->orWhereNotNull('guru_id');
        })->whereNotNull('guru_id');
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeTanggal(Builder $query, $tanggal): Builder
    {
        return $query->whereDate('tanggal', $tanggal);
    }

    /**
     * Scope untuk absensi hari ini
     */
    public function scopeHariIni(Builder $query): Builder
    {
        return $query->whereDate('tanggal', today());
    }

    /**
     * Scope untuk filter berdasarkan bulan
     */
    public function scopeBulan(Builder $query, $bulan): Builder
    {
        return $query->whereMonth('tanggal', $bulan);
    }

    /**
     * Scope untuk filter berdasarkan tahun
     */
    public function scopeTahun(Builder $query, $tahun): Builder
    {
        return $query->whereYear('tanggal', $tahun);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeStatus(Builder $query, $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk absensi yang belum checkout
     */
    public function scopeBelumCheckout(Builder $query): Builder
    {
        return $query->whereNull('jam_keluar');
    }

    /**
     * Scope untuk absensi yang sudah checkout
     */
    public function scopeSudahCheckout(Builder $query): Builder
    {
        return $query->whereNotNull('jam_keluar');
    }

    /**
     * Helper untuk cek apakah sudah absen hari ini untuk siswa tertentu
     */
    public static function sudahAbsenHariIni($siswaId): bool
    {
        return self::where('siswa_id', $siswaId)
            ->whereDate('tanggal', today())
            ->exists();
    }

    /**
     * Helper untuk cek apakah sudah absen hari ini untuk guru tertentu
     */
    public static function sudahAbsenHariIniGuru($guruId): bool
    {
        return self::where('guru_id', $guruId)
            ->whereDate('tanggal', today())
            ->exists();
    }

    /**
     * Helper untuk mengambil absensi siswa hari ini
     */
    public static function getAbsensiSiswaHariIni($siswaId)
    {
        return self::where('siswa_id', $siswaId)
            ->whereDate('tanggal', today())
            ->first();
    }

    /**
     * Helper untuk mengambil absensi guru hari ini
     */
    public static function getAbsensiGuruHariIni($guruId)
    {
        return self::where('guru_id', $guruId)
            ->whereDate('tanggal', today())
            ->first();
    }

    /**
     * Hitung total keterlambatan (menit)
     */
    public function getTerlambatAttribute(): ?int
    {
        if (!$this->jam_masuk) {
            return null;
        }
        
        $batasWaktu = '07:00:00';
        if ($this->jam_masuk->format('H:i:s') > $batasWaktu) {
            $diff = $this->jam_masuk->diffInMinutes(\Carbon\Carbon::parse($batasWaktu));
            return $diff;
        }
        
        return 0;
    }

    /**
     * Hitung durasi absensi
     */
    public function getDurasiAttribute(): ?string
    {
        if (!$this->jam_masuk || !$this->jam_keluar) {
            return null;
        }
        
        $diff = $this->jam_masuk->diff($this->jam_keluar);
        return $diff->format('%h jam %i menit');
    }

    /**
     * Accessor untuk badge status (HTML)
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            self::STATUS_HADIR => 'success',
            self::STATUS_SAKIT => 'warning',
            self::STATUS_IZIN => 'info',
            self::STATUS_ALPHA => 'danger',
            self::STATUS_TERLAMBAT => 'secondary'
        ];
        
        $color = $badges[$this->status] ?? 'secondary';
        $statusText = self::getStatusOptions()[$this->status] ?? $this->status;
        
        return "<span class='badge bg-{$color} px-3 py-2'>{$statusText}</span>";
    }

    /**
     * Accessor untuk badge type (HTML)
     */
    public function getTypeBadgeAttribute(): string
    {
        $jenis = $this->jenis_absensi ?? ($this->siswa_id ? self::TYPE_SISWA : self::TYPE_GURU);
        
        $badges = [
            self::TYPE_SISWA => 'primary',
            self::TYPE_GURU => 'dark'
        ];
        
        $color = $badges[$jenis] ?? 'secondary';
        $typeText = self::getTypeOptions()[$jenis] ?? $jenis;
        
        return "<span class='badge bg-{$color} px-3 py-2'>{$typeText}</span>";
    }

    /**
     * Accessor untuk status label (text only)
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatusOptions()[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Accessor untuk format waktu masuk (H:i)
     */
    public function getWaktuMasukFormattedAttribute(): string
    {
        return $this->jam_masuk ? $this->jam_masuk->format('H:i') : '-';
    }

    /**
     * Accessor untuk format waktu keluar (H:i)
     */
    public function getWaktuKeluarFormattedAttribute(): string
    {
        return $this->jam_keluar ? $this->jam_keluar->format('H:i') : '-';
    }

    /**
     * Accessor untuk format tanggal (d/m/Y)
     */
    public function getTanggalFormattedAttribute(): string
    {
        return $this->tanggal ? $this->tanggal->format('d/m/Y') : '-';
    }

    /**
     * Accessor untuk nama hari
     */
    public function getNamaHariAttribute(): string
    {
        return $this->tanggal ? $this->tanggal->translatedFormat('l') : '-';
    }

    /**
     * Mutator untuk status (auto convert to lowercase)
     */
    public function setStatusAttribute($value): void
    {
        $this->attributes['status'] = strtolower($value);
    }

    /**
     * Mutator untuk jenis_absensi (auto convert to lowercase)
     */
    public function setJenisAbsensiAttribute($value): void
    {
        $this->attributes['jenis_absensi'] = strtolower($value);
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        
        // Auto set jenis_absensi sebelum create
        static::creating(function ($model) {
            if ($model->siswa_id && !$model->jenis_absensi) {
                $model->jenis_absensi = self::TYPE_SISWA;
            }
            if ($model->guru_id && !$model->jenis_absensi) {
                $model->jenis_absensi = self::TYPE_GURU;
            }
            
            // Set user_id dan user_type untuk polymorphic
            if ($model->siswa_id && !$model->user_id) {
                $model->user_id = $model->siswa_id;
                $model->user_type = Siswa::class;
            }
            if ($model->guru_id && !$model->user_id) {
                $model->user_id = $model->guru_id;
                $model->user_type = Guru::class;
            }
        });
    }

    /**
     * Get the table name
     */
    public function getTable()
    {
        return 'absensi';
    }
}