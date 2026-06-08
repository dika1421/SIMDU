<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class JadwalSholat extends Model
{
    use HasFactory;

    protected $table = 'jadwal_sholat';
    
    protected $fillable = ['tanggal', 'subuh', 'dzuhur', 'ashar', 'maghrib', 'isya', 'status', 'catatan'];
    
    protected $casts = [
        'tanggal' => 'date',
    ];
    
    public static function getSchedule($tanggal)
    {
        return self::firstOrCreate(
            ['tanggal' => $tanggal],
            [
                'subuh' => '04:30:00',
                'dzuhur' => '12:00:00',
                'ashar' => '15:30:00',
                'maghrib' => '18:00:00',
                'isya' => '19:30:00'
            ]
        );
    }
}