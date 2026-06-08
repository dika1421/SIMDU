<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AbsensiSholat extends Model
{
    use HasFactory;

    protected $table = 'absensi_sholat';
    
    protected $fillable = [
        'role', 'user_id', 'tanggal', 'sholat', 'status', 
        'waktu_absen', 'keterangan', 'lokasi', 'latitude', 
        'longitude', 'foto'
    ];
    
    protected $casts = [
        'tanggal' => 'date',
        'waktu_absen' => 'datetime',
    ];
    
    const SHOLAT_LIST = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
    
    public function user()
    {
        if ($this->role === 'siswa') {
            return $this->belongsTo(Siswa::class, 'user_id');
        }
        return $this->belongsTo(Guru::class, 'user_id');
    }
}