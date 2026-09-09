<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StrukturOrganisasi extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * NAMA TABEL - SESUAIKAN DENGAN DATABASE
     */
    protected $table = 'struktur_organisasi';

    /**
     * FIELD YANG DAPAT DIISI
     * SESUAIKAN DENGAN STRUKTUR TABEL DI DATABASE
     */
    protected $fillable = [
        'nama_jabatan',
        'nama_pejabat',
        'nip',
        'deskripsi_tugas',
        'urutan',
        'parent_id',
        'guru_id',
        'foto',
        'status',
    ];

    /**
     * CASTING TIPE DATA
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * =============================================
     * RELASI
     * =============================================
     */

    /**
     * Relasi ke parent (atasan)
     */
    public function parent()
    {
        return $this->belongsTo(StrukturOrganisasi::class, 'parent_id');
    }

    /**
     * Relasi ke children (bawahan)
     */
    public function children()
    {
        return $this->hasMany(StrukturOrganisasi::class, 'parent_id');
    }

    /**
     * Relasi ke guru (jika dijabat oleh guru)
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    /**
     * =============================================
     * ACCESSORS
     * =============================================
     */

    /**
     * Get nama jabatan (fallback untuk kompatibilitas)
     */
    public function getNamaAttribute()
    {
        return $this->nama_jabatan ?? $this->nama ?? '-';
    }

    /**
     * Get nama pejabat (fallback untuk kompatibilitas)
     */
    public function getPejabatAttribute()
    {
        return $this->nama_pejabat ?? $this->jabatan ?? '-';
    }

    /**
     * Get deskripsi (fallback untuk kompatibilitas)
     */
    public function getDeskripsiAttribute()
    {
        return $this->deskripsi_tugas ?? $this->deskripsi ?? '-';
    }

    /**
     * =============================================
     * SCOPES
     * =============================================
     */

    /**
     * Scope untuk data aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope untuk root (tidak punya atasan)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope untuk data yang memiliki atasan
     */
    public function scopeSub($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * =============================================
     * HELPERS
     * =============================================
     */

    /**
     * Cek apakah ini root (tidak punya atasan)
     */
    public function isRoot()
    {
        return is_null($this->parent_id);
    }

    /**
     * Cek apakah memiliki bawahan
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    /**
     * Ambil semua bawahan (rekursif)
     */
    public function getAllChildren()
    {
        $children = collect();
        foreach ($this->children as $child) {
            $children->push($child);
            $children = $children->merge($child->getAllChildren());
        }
        return $children;
    }
}