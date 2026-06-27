<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pesan;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KomunikasiController extends Controller
{
    /**
     * Display a listing of messages.
     */
    public function index()
    {
        try {
            $user = auth()->user();
            
            // Ambil pesan yang diterima user (belum dihapus)
            $pesanMasuk = Pesan::whereHas('penerimaPesan', function($q) use ($user) {
                $q->where('penerima_id', $user->id)
                  ->where('status', '!=', 'dihapus');
            })
            ->with(['pengirim', 'penerimaPesan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
            // Ambil pesan yang dikirim user
            $pesanKeluar = Pesan::where('pengirim_id', $user->id)
                ->with(['penerimaPesan.penerima'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            
            // Hitung pesan belum dibaca
            $belumDibaca = Pesan::whereHas('penerimaPesan', function($q) use ($user) {
                $q->where('penerima_id', $user->id)
                  ->where('status', 'terkirim');
            })->count();
            
            return view('guru.komunikasi.index', compact('pesanMasuk', 'pesanKeluar', 'belumDibaca'));
            
        } catch (\Exception $e) {
            Log::error('Error in komunikasi index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Show the form for creating a new message.
     */
    public function create()
    {
        try {
            $user = auth()->user();
            
            // Ambil semua user kecuali diri sendiri
            $users = User::where('id', '!=', $user->id)
                         ->where('status', 'aktif')
                         ->orderBy('name')
                         ->get();
            
            // Kelompokkan berdasarkan role
            $guru = $users->where('role', 'guru');
            $siswa = $users->where('role', 'siswa');
            $administrasi = $users->where('role', 'administrasi');
            $kepalaSekolah = $users->where('role', 'kepala_sekolah');
            
            return view('guru.komunikasi.create', compact('guru', 'siswa', 'administrasi', 'kepalaSekolah'));
            
        } catch (\Exception $e) {
            Log::error('Error in komunikasi create: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Store a newly created message.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
                'isi' => 'required|string',
                'penerima_id' => 'required|array',
                'penerima_id.*' => 'exists:users,id'
            ]);
            
            DB::beginTransaction();
            
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
                           ->with('success', '✅ Pesan berhasil dikirim!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in komunikasi store: ' . $e->getMessage());
            return back()->with('error', '❌ Gagal mengirim pesan: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the specified message.
     */
    public function show($id)
    {
        try {
            $user = auth()->user();
            
            $pesan = Pesan::with(['pengirim', 'penerimaPesan.penerima'])
                ->where(function($q) use ($user, $id) {
                    $q->where('id', $id)
                      ->where(function($q2) use ($user) {
                          $q2->where('pengirim_id', $user->id)
                             ->orWhereHas('penerimaPesan', function($q3) use ($user) {
                                 $q3->where('penerima_id', $user->id)
                                    ->where('status', '!=', 'dihapus');
                             });
                      });
                })
                ->firstOrFail();
            
            // Tandai sebagai dibaca jika user adalah penerima
            if ($user->id != $pesan->pengirim_id) {
                $pesan->penerimaPesan()
                      ->where('penerima_id', $user->id)
                      ->where('status', 'terkirim')
                      ->update([
                          'status' => 'dibaca',
                          'tanggal_baca' => now()
                      ]);
            }
            
            return view('guru.komunikasi.show', compact('pesan'));
            
        } catch (\Exception $e) {
            Log::error('Error in komunikasi show: ' . $e->getMessage());
            return redirect()->route('guru.komunikasi.index')
                           ->with('error', 'Pesan tidak ditemukan.');
        }
    }
    
    /**
     * Remove the specified message.
     */
    public function destroy($id)
    {
        try {
            $user = auth()->user();
            
            $pesan = Pesan::findOrFail($id);
            
            // Cek apakah user adalah pengirim atau penerima
            $isPengirim = $pesan->pengirim_id == $user->id;
            $isPenerima = $pesan->penerimaPesan()
                ->where('penerima_id', $user->id)
                ->exists();
            
            if (!$isPengirim && !$isPenerima) {
                return redirect()->route('guru.komunikasi.index')
                               ->with('error', 'Anda tidak memiliki akses untuk menghapus pesan ini.');
            }
            
            if ($isPengirim) {
                // Jika pengirim, hapus pesan secara permanen
                $pesan->delete();
            } else {
                // Jika penerima, soft delete di pivot
                $pesan->penerimaPesan()
                      ->where('penerima_id', $user->id)
                      ->update([
                          'status' => 'dihapus',
                          'tanggal_dihapus' => now()
                      ]);
            }
            
            return redirect()->route('guru.komunikasi.index')
                           ->with('success', '✅ Pesan berhasil dihapus!');
                           
        } catch (\Exception $e) {
            Log::error('Error in komunikasi destroy: ' . $e->getMessage());
            return redirect()->route('guru.komunikasi.index')
                           ->with('error', '❌ Gagal menghapus pesan: ' . $e->getMessage());
        }
    }
    
    /**
     * Mark message as read.
     */
    public function markAsRead($id)
    {
        try {
            $user = auth()->user();
            
            $pesan = Pesan::findOrFail($id);
            
            $pesan->penerimaPesan()
                  ->where('penerima_id', $user->id)
                  ->where('status', 'terkirim')
                  ->update([
                      'status' => 'dibaca',
                      'tanggal_baca' => now()
                  ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Pesan ditandai sebagai sudah dibaca'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in mark as read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Mark all messages as read.
     */
    public function markAllAsRead()
    {
        try {
            $user = auth()->user();
            
            Pesan::whereHas('penerimaPesan', function($q) use ($user) {
                $q->where('penerima_id', $user->id)
                  ->where('status', 'terkirim');
            })
            ->each(function($pesan) use ($user) {
                $pesan->penerimaPesan()
                      ->where('penerima_id', $user->id)
                      ->where('status', 'terkirim')
                      ->update([
                          'status' => 'dibaca',
                          'tanggal_baca' => now()
                      ]);
            });
            
            return redirect()->route('guru.komunikasi.index')
                           ->with('success', '✅ Semua pesan ditandai sebagai sudah dibaca');
                           
        } catch (\Exception $e) {
            Log::error('Error in mark all as read: ' . $e->getMessage());
            return redirect()->route('guru.komunikasi.index')
                           ->with('error', '❌ Gagal menandai semua pesan: ' . $e->getMessage());
        }
    }
    
    /**
     * Get unread count (for AJAX).
     */
    public function getUnreadCount()
    {
        try {
            $user = auth()->user();
            
            $count = Pesan::whereHas('penerimaPesan', function($q) use ($user) {
                $q->where('penerima_id', $user->id)
                  ->where('status', 'terkirim');
            })->count();
            
            return response()->json([
                'success' => true,
                'count' => $count
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in get unread count: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Reply to a message.
     */
    public function reply(Request $request, $id)
    {
        try {
            $request->validate([
                'isi' => 'required|string',
            ]);
            
            $pesanAsli = Pesan::findOrFail($id);
            
            DB::beginTransaction();
            
            // Buat pesan balasan
            $pesan = Pesan::create([
                'judul' => 'Re: ' . $pesanAsli->judul,
                'isi' => $request->isi,
                'pengirim_id' => auth()->id(),
                'jenis' => 'personal',
                'status' => 'terkirim',
                'is_urgent' => $request->is_urgent ?? false,
                'tanggal_kirim' => now(),
                'parent_id' => $id // Untuk tracking balasan
            ]);
            
            // Tambahkan penerima (pengirim asli)
            $pesan->penerimaPesan()->create([
                'penerima_id' => $pesanAsli->pengirim_id,
                'status' => 'terkirim'
            ]);
            
            DB::commit();
            
            return redirect()->route('guru.komunikasi.show', $pesan->id)
                           ->with('success', '✅ Balasan berhasil dikirim!');
                           
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in komunikasi reply: ' . $e->getMessage());
            return back()->with('error', '❌ Gagal mengirim balasan: ' . $e->getMessage());
        }
    }
}