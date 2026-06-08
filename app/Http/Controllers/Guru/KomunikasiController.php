<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pesan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KomunikasiController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Ambil pesan yang diterima user
        $pesan = Pesan::whereHas('penerimaPesan', function($q) use ($user) {
            $q->where('penerima_id', $user->id)
              ->where('status', '!=', 'dihapus');
        })
        ->with(['pengirim', 'penerimaPesan'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        
        // Hitung pesan belum dibaca
        $belumDibaca = Pesan::whereHas('penerimaPesan', function($q) use ($user) {
            $q->where('penerima_id', $user->id)
              ->where('status', 'terkirim'); // Pesan yang belum dibaca statusnya 'terkirim'
        })->count();
        
        return view('guru.komunikasi.index', compact('pesan', 'belumDibaca'));
    }
    
    public function create()
    {
        // Ambil semua user kecuali diri sendiri
        $users = User::where('id', '!=', auth()->id())
                     ->orderBy('name')
                     ->get();
        
        // Kelompokkan berdasarkan role
        $guru = $users->where('role', 'guru');
        $siswa = $users->where('role', 'siswa');
        $administrasi = $users->where('role', 'administrasi');
        
        return view('guru.komunikasi.create', compact('guru', 'siswa', 'administrasi'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'penerima_id' => 'required|array',
            'penerima_id.*' => 'exists:users,id'
        ]);
        
        DB::beginTransaction();
        try {
            // Buat pesan
            $pesan = Pesan::create([
                'judul' => $request->judul,
                'isi' => $request->isi,
                'pengirim_id' => auth()->id(),
                'jenis' => count($request->penerima_id) > 1 ? 'broadcast' : 'personal',
                'status' => 'terkirim',
                'is_urgent' => $request->is_urgent ?? false,
                'tanggal_kirim' => now()
            ]);
            
            // Tambahkan penerima
            foreach ($request->penerima_id as $penerimaId) {
                $pesan->penerimaPesan()->create([
                    'penerima_id' => $penerimaId,
                    'status' => 'terkirim'
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('guru.komunikasi.index')
                           ->with('success', 'Pesan berhasil dikirim');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal mengirim pesan: ' . $e->getMessage());
        }
    }
    
    public function show($id)
    {
        $pesan = Pesan::with(['pengirim', 'penerimaPesan.penerima'])->findOrFail($id);
        
        // Tandai sebagai dibaca jika user adalah penerima
        if (auth()->id() != $pesan->pengirim_id) {
            $pesan->penerimaPesan()
                  ->where('penerima_id', auth()->id())
                  ->where('status', 'terkirim')
                  ->update([
                      'status' => 'dibaca',
                      'tanggal_baca' => now()
                  ]);
        }
        
        return view('guru.komunikasi.show', compact('pesan'));
    }
    
    public function destroy($id)
    {
        $pesan = Pesan::findOrFail($id);
        
        // Hapus untuk user tertentu (soft delete di pivot)
        $pesan->penerimaPesan()
              ->where('penerima_id', auth()->id())
              ->update([
                  'status' => 'dihapus',
                  'tanggal_dihapus' => now()
              ]);
        
        return redirect()->route('guru.komunikasi.index')
                       ->with('success', 'Pesan berhasil dihapus');
    }
    
    public function markAsRead($id)
    {
        $pesan = Pesan::findOrFail($id);
        
        $pesan->penerimaPesan()
              ->where('penerima_id', auth()->id())
              ->where('status', 'terkirim')
              ->update([
                  'status' => 'dibaca',
                  'tanggal_baca' => now()
              ]);
        
        return response()->json(['success' => true]);
    }
    
    public function getUnreadCount()
    {
        $count = Pesan::whereHas('penerimaPesan', function($q) {
            $q->where('penerima_id', auth()->id())
              ->where('status', 'terkirim');
        })->count();
        
        return response()->json(['count' => $count]);
    }
}