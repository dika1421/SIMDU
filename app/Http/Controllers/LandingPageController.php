<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LandingPageController extends Controller
{
    /**
     * Tampilkan halaman landing page dengan data galeri dan statistik
     */
    public function index()
    {
        // 🔥 AMBIL DATA GALERI YANG AKTIF
        $galleries = Gallery::where('status', 'active')
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // 🔥 AMBIL DATA STATISTIK DARI DATABASE
        $totalSiswa = Siswa::where('status', 'aktif')->count();
        $totalGuru = Guru::where('status', 'aktif')->count();
        $totalKelas = Kelas::count();
        $totalJurusan = Jurusan::count();

        // 🔥 DEBUG: Cek data galeri (untuk debugging)
        Log::info('=== DATA GALERI LANDING PAGE ===');
        Log::info('Jumlah galeri: ' . $galleries->count());
        
        foreach ($galleries as $gallery) {
            Log::info('Galeri:', [
                'id' => $gallery->id,
                'title' => $gallery->title,
                'status' => $gallery->status,
                'image' => $gallery->image,
                'url' => asset('storage/galleries/' . $gallery->image),
            ]);
        }

        return view('landing.index', compact(
            'galleries',
            'totalSiswa',
            'totalGuru',
            'totalKelas',
            'totalJurusan'
        ));
    }

    /**
     * Tampilkan halaman tentang kami
     */
    public function about()
    {
        return view('landing.about');
    }

    /**
     * Tampilkan halaman fitur
     */
    public function features()
    {
        return view('landing.features');
    }

    /**
     * Tampilkan halaman kontak
     */
    public function contact()
    {
        return view('landing.contact');
    }
}