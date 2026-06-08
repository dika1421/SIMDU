<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Pesan;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KomunikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
            
            // Ambil pesan yang diterima oleh user ini
            $pesanDiterima = Pesan::whereHas('penerimaPesan', function($q) use ($user) {
                $q->where('penerima_id', $user->id)
                  ->where('status', '!=', 'dihapus');
            })
            ->with(['pengirim', 'penerimaPesan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
            // Ambil pesan yang dikirim oleh user ini
            $pesanDikirim = Pesan::where('pengirim_id', $user->id)
                ->where('status', '!=', 'dihapus')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            
            // Hitung pesan belum dibaca
            $belumDibaca = Pesan::whereHas('penerimaPesan', function($q) use ($user) {
                $q->where('penerima_id', $user->id)
                  ->where('status', 'terkirim');
            })->count();
            
            return view('administrasi.komunikasi.index', compact('pesanDiterima', 'pesanDikirim', 'belumDibaca'));
            
        } catch (\Exception $e) {
            Log::error('Error in komunikasi index: ' . $e->getMessage());
            
            $pesanDiterima = collect([]);
            $pesanDikirim = collect([]);
            $belumDibaca = 0;
            
            return view('administrasi.komunikasi.index', compact('pesanDiterima', 'pesanDikirim', 'belumDibaca'))
                ->with('error', 'Gagal memuat pesan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            // Ambil data siswa dengan relasi user dan kelas
            $siswa = Siswa::with(['user', 'kelas'])->get();
            
            // Ambil data guru dengan relasi user
            $guru = Guru::with('user')->get();
            
            // Ambil data administrasi (user dengan role administrasi selain diri sendiri)
            $administrasi = User::where('role', 'administrasi')
                ->where('id', '!=', auth()->id())
                ->get();
            
            return view('administrasi.komunikasi.create', compact('guru', 'siswa', 'administrasi'));
            
        } catch (\Exception $e) {
            Log::error('Error in komunikasi create: ' . $e->getMessage());
            return redirect()->route('administrasi.komunikasi.index')
                ->with('error', 'Gagal memuat form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Log data yang masuk untuk debugging
            Log::info('Data yang diterima:', $request->all());
            
            // Validasi
            $request->validate([
                'judul' => 'required|string|max:255',
                'isi' => 'required|string',
                'jenis' => 'required|in:personal,broadcast',
            ]);
            
            // Jika jenis personal, wajib ada penerima
            if ($request->jenis == 'personal') {
                $request->validate([
                    'penerima_id' => 'required|array|min:1',
                    'penerima_id.*' => 'exists:users,id'
                ]);
            }
            
            DB::beginTransaction();
            
            $user = auth()->user();
            $penerimaIds = [];
            
            // Jika broadcast, ambil semua user berdasarkan role
            if ($request->jenis == 'broadcast') {
                // Ambil semua user kecuali pengirim
                $penerimaIds = User::where('id', '!=', $user->id)->pluck('id')->toArray();
            } else {
                // Personal: ambil dari input
                $penerimaIds = $request->penerima_id;
            }
            
            // Cek apakah ada penerima
            if (empty($penerimaIds)) {
                throw new \Exception('Tidak ada penerima yang dipilih');
            }
            
            // Buat pesan
            $pesan = Pesan::create([
                'judul' => $request->judul,
                'isi' => $request->isi,
                'pengirim_id' => $user->id,
                'pengirim_type' => 'App\\Models\\User',
                'jenis' => $request->jenis,
                'status' => 'terkirim',
                'is_urgent' => $request->has('is_urgent') ? true : false,
                'tanggal_kirim' => now()
            ]);
            
            // Tambahkan penerima
            foreach ($penerimaIds as $penerimaId) {
                $pesan->penerimaPesan()->create([
                    'penerima_id' => $penerimaId,
                    'penerima_type' => 'App\\Models\\User',
                    'status' => 'terkirim'
                ]);
            }
            
            DB::commit();
            
            $message = $request->jenis == 'broadcast' 
                ? 'Broadcast berhasil dikirim ke ' . count($penerimaIds) . ' penerima'
                : 'Pesan berhasil dikirim ke ' . count($penerimaIds) . ' penerima';
            
            return redirect()->route('administrasi.komunikasi.index')
                ->with('success', $message);
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in komunikasi store: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengirim pesan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
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
            
            return view('administrasi.komunikasi.show', compact('pesan'));
            
        } catch (\Exception $e) {
            Log::error('Error in komunikasi show: ' . $e->getMessage());
            return redirect()->route('administrasi.komunikasi.index')
                ->with('error', 'Pesan tidak ditemukan');
        }
    }

    /**
     * Broadcast form
     */
    public function broadcastForm()
    {
        try {
            $targets = [
                'siswa' => User::where('role', 'siswa')->count(),
                'guru' => User::where('role', 'guru')->count(),
                'administrasi' => User::where('role', 'administrasi')
                    ->where('id', '!=', auth()->id())
                    ->count(),
            ];
            
            return view('administrasi.komunikasi.broadcast', compact('targets'));
            
        } catch (\Exception $e) {
            Log::error('Error in broadcast form: ' . $e->getMessage());
            return redirect()->route('administrasi.komunikasi.index')
                ->with('error', 'Gagal memuat form broadcast: ' . $e->getMessage());
        }
    }

    /**
     * Send broadcast message
     */
    public function sendBroadcast(Request $request)
    {
        try {
            $request->validate([
                'judul' => 'required|string|max:255',
                'isi' => 'required|string',
                'target' => 'required|array',
                'target.*' => 'in:siswa,guru,administrasi'
            ]);
            
            DB::beginTransaction();
            
            $user = auth()->user();
            $penerimaIds = [];
            
            // Kumpulkan penerima berdasarkan target
            if (in_array('siswa', $request->target)) {
                $siswaIds = User::where('role', 'siswa')->pluck('id')->toArray();
                $penerimaIds = array_merge($penerimaIds, $siswaIds);
            }
            
            if (in_array('guru', $request->target)) {
                $guruIds = User::where('role', 'guru')->pluck('id')->toArray();
                $penerimaIds = array_merge($penerimaIds, $guruIds);
            }
            
            if (in_array('administrasi', $request->target)) {
                $adminIds = User::where('role', 'administrasi')
                    ->where('id', '!=', $user->id)
                    ->pluck('id')
                    ->toArray();
                $penerimaIds = array_merge($penerimaIds, $adminIds);
            }
            
            // Hapus duplikat
            $penerimaIds = array_unique($penerimaIds);
            
            if (empty($penerimaIds)) {
                throw new \Exception('Tidak ada penerima yang dipilih');
            }
            
            // Buat pesan broadcast
            $pesan = Pesan::create([
                'judul' => $request->judul,
                'isi' => $request->isi,
                'pengirim_id' => $user->id,
                'pengirim_type' => 'App\\Models\\User',
                'jenis' => 'broadcast',
                'status' => 'terkirim',
                'is_urgent' => $request->has('is_urgent') ? true : false,
                'tanggal_kirim' => now()
            ]);
            
            // Tambahkan semua penerima
            foreach ($penerimaIds as $penerimaId) {
                $pesan->penerimaPesan()->create([
                    'penerima_id' => $penerimaId,
                    'penerima_type' => 'App\\Models\\User',
                    'status' => 'terkirim'
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('administrasi.komunikasi.index')
                ->with('success', 'Broadcast berhasil dikirim ke ' . count($penerimaIds) . ' penerima');
                
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in send broadcast: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengirim broadcast: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pesan = Pesan::findOrFail($id);
            
            // Hapus untuk user tertentu (soft delete di pivot)
            $pesan->penerimaPesan()
                ->where('penerima_id', auth()->id())
                ->update([
                    'status' => 'dihapus',
                    'tanggal_dihapus' => now()
                ]);
            
            return redirect()->route('administrasi.komunikasi.index')
                ->with('success', 'Pesan berhasil dihapus');
                
        } catch (\Exception $e) {
            Log::error('Error in komunikasi destroy: ' . $e->getMessage());
            return redirect()->route('administrasi.komunikasi.index')
                ->with('error', 'Gagal menghapus pesan');
        }
    }
    
    /**
     * Get unread messages count (for AJAX)
     */
    public function getUnreadCount()
    {
        try {
            $count = Pesan::whereHas('penerimaPesan', function($q) {
                $q->where('penerima_id', auth()->id())
                  ->where('status', 'terkirim');
            })->count();
            
            return response()->json(['count' => $count]);
            
        } catch (\Exception $e) {
            return response()->json(['count' => 0, 'error' => $e->getMessage()]);
        }
    }
}