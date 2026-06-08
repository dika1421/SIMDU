<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembayaranLain extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_lain';

    protected $fillable = [
        'no_transaksi',
        'siswa_id',
        'kategori',
        'jumlah',
        'metode_bayar',
        'tanggal_bayar',
        'keterangan',
        'status'
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah' => 'decimal:2'
    ];

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}