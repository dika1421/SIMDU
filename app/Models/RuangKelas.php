<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuangKelas extends Model
{
    use HasFactory;

    protected $table = 'ruang_kelas';
    
    protected $fillable = [
        'kode',
        'nama',
        'kapasitas',
        'lokasi',
        'keterangan',
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'ruang', 'kode');
    }
}