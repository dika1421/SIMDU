<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\KalenderAkademik;
use Illuminate\Http\Request;

class KalenderController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        
        // Ambil event berdasarkan tahun
        $events = KalenderAkademik::where('status', 'aktif')
            ->where(function($query) use ($tahun) {
                $query->whereYear('tanggal_mulai', $tahun)
                      ->orWhereYear('tanggal_selesai', $tahun);
            })
            ->orderBy('tanggal_mulai', 'asc')
            ->get();
        
        // Kelompokkan berdasarkan jenis
        $libur = $events->where('jenis', 'libur');
        $ujian = $events->where('jenis', 'ujian');
        $acara = $events->where('jenis', 'acara');
        $lainnya = $events->where('jenis', 'lainnya');
        
        // Data untuk filter
        $tahunList = range(date('Y') - 2, date('Y') + 2);
        
        return view('guru.kalender.index', compact('events', 'libur', 'ujian', 'acara', 'lainnya', 'tahun', 'tahunList'));
    }
    
    public function getEvents(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        
        $events = KalenderAkademik::where('status', 'aktif')
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('tanggal_mulai', [$start, $end])
                      ->orWhereBetween('tanggal_selesai', [$start, $end])
                      ->orWhere(function($q) use ($start, $end) {
                          $q->where('tanggal_mulai', '<=', $start)
                            ->where('tanggal_selesai', '>=', $end);
                      });
            })
            ->get();
        
        $formattedEvents = $events->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->judul,
                'start' => $event->tanggal_mulai,
                'end' => $event->tanggal_selesai ?? $event->tanggal_mulai,
                'color' => $event->warna,
                'description' => $event->deskripsi,
                'type' => $event->jenis,
                'allDay' => true
            ];
        });
        
        return response()->json($formattedEvents);
    }
    
    public function show($id)
    {
        $event = KalenderAkademik::findOrFail($id);
        return view('guru.kalender.show', compact('event'));
    }
}