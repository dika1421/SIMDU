<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\KalenderAkademik;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KalenderController extends Controller
{
    private function getSiswa()
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        if (!$siswa) {
            $kelas = \App\Models\Kelas::first();
            $siswa = Siswa::create([
                'user_id' => $user->id,
                'nis' => 'SIS' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'nisn' => 'NSN' . str_pad($user->id, 8, '0', STR_PAD_LEFT),
                'nama_lengkap' => $user->name,
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2008-01-01',
                'kelas_id' => $kelas ? $kelas->id : null,
                'status' => 'aktif',
                'tahun_masuk' => date('Y')
            ]);
        }
        
        return $siswa;
    }
    
    public function index(Request $request)
    {
        $this->getSiswa();
        
        $bulan = $request->bulan ?? Carbon::now()->month;
        $tahun = $request->tahun ?? Carbon::now()->year;
        
        $events = KalenderAkademik::where('status', 'aktif')
            ->where(function($query) use ($tahun) {
                $query->whereYear('tanggal_mulai', $tahun)
                      ->orWhereYear('tanggal_selesai', $tahun);
            })
            ->orderBy('tanggal_mulai', 'asc')
            ->get()
            ->map(function($event) {
                return [
                    'id' => $event->id,
                    'judul' => $event->judul,
                    'deskripsi' => $event->deskripsi,
                    'jenis' => $event->jenis,
                    'tanggal_mulai' => $event->tanggal_mulai,
                    'tanggal_selesai' => $event->tanggal_selesai,
                    'warna' => $this->getWarnaJenis($event->jenis)
                ];
            });
        
        $ujian = KalenderAkademik::where('jenis', 'ujian')
            ->where('status', 'aktif')
            ->whereYear('tanggal_mulai', $tahun)
            ->orderBy('tanggal_mulai', 'asc')
            ->get();
        
        $kegiatan = KalenderAkademik::where('jenis', 'acara')
            ->where('status', 'aktif')
            ->whereYear('tanggal_mulai', $tahun)
            ->orderBy('tanggal_mulai', 'asc')
            ->get();
        
        $libur = KalenderAkademik::where('jenis', 'libur')
            ->where('status', 'aktif')
            ->whereYear('tanggal_mulai', $tahun)
            ->orderBy('tanggal_mulai', 'asc')
            ->get();
        
        $eventsBulanIni = $events->filter(function($event) use ($bulan, $tahun) {
            $startMonth = Carbon::parse($event['tanggal_mulai'])->month;
            return $startMonth == $bulan;
        });
        
        $bulanList = $this->getBulanList();
        $tahunList = range(date('Y') - 2, date('Y') + 2);
        
        return view('siswa.kalender.index', compact(
            'events', 'eventsBulanIni', 'ujian', 'kegiatan', 'libur',
            'bulan', 'tahun', 'bulanList', 'tahunList'
        ));
    }
    
    public function getEvents(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        
        $events = KalenderAkademik::where('status', 'aktif')
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('tanggal_mulai', [$start, $end])
                      ->orWhereBetween('tanggal_selesai', [$start, $end]);
            })
            ->get();
        
        $formattedEvents = $events->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->judul,
                'start' => $event->tanggal_mulai,
                'end' => $event->tanggal_selesai ?? $event->tanggal_mulai,
                'color' => $this->getWarnaJenis($event->jenis),
                'description' => $event->deskripsi,
                'type' => $event->jenis
            ];
        });
        
        return response()->json($formattedEvents);
    }
    
    private function getWarnaJenis($jenis)
    {
        $warna = [
            'libur' => '#ffc107',
            'ujian' => '#dc3545',
            'acara' => '#28a745',
            'rapat' => '#17a2b8',
            'pendaftaran' => '#6c757d',
            'lainnya' => '#6c757d'
        ];
        return $warna[$jenis] ?? '#007bff';
    }
    
    private function getBulanList()
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
    }
}