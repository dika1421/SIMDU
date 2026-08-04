<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Persetujuan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PersetujuanController extends Controller
{
    /**
     * Menampilkan daftar pengajuan dengan filter
     */
    public function index(Request $request)
    {
        try {
            $query = Persetujuan::with('user');

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('jenis')) {
                $query->where('jenis', $request->jenis);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhereHas('user', function($sub) use ($search) {
                          $sub->where('name', 'like', "%{$search}%");
                      });
                });
            }

            $persetujuan = $query->orderBy('created_at', 'desc')->paginate(20);

            $total = Persetujuan::count();
            $pending = Persetujuan::where('status', 'pending')->count();
            $approved = Persetujuan::where('status', 'approved')->count();
            $rejected = Persetujuan::where('status', 'rejected')->count();
            $revised = Persetujuan::where('status', 'revised')->count();

            $statuses = ['pending', 'approved', 'rejected', 'revised'];
            $jenisList = ['kegiatan', 'anggaran', 'laporan', 'izin', 'lainnya'];

            return view('kepala-sekolah.persetujuan.index', compact(
                'persetujuan', 
                'statuses', 
                'jenisList',
                'total',
                'pending',
                'approved',
                'rejected',
                'revised'
            ));

        } catch (\Exception $e) {
            Log::error('Persetujuan Index Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $pengajuList = User::where('role', '!=', 'kepala_sekolah')
                ->orderBy('name')
                ->get();

            return view('kepala-sekolah.persetujuan.create', compact('pengajuList'));
        } catch (\Exception $e) {
            Log::error('Persetujuan Create Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'judul' => 'required|string|max:255',
                'jenis' => 'required|string|in:kegiatan,anggaran,laporan,izin,lainnya',
                'deskripsi' => 'required|string|min:10',
                'nominal' => 'nullable|numeric|min:0',
                'tanggal_pelaksanaan' => 'nullable|date',
                'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $lampiranPath = null;
            if ($request->hasFile('lampiran')) {
                $file = $request->file('lampiran');
                $filename = time() . '_' . $file->getClientOriginalName();
                $lampiranPath = $file->storeAs('uploads/persetujuan', $filename, 'public');
            }

            $persetujuan = Persetujuan::create([
                'judul' => $request->judul,
                'jenis' => $request->jenis,
                'deskripsi' => $request->deskripsi,
                'nominal' => $request->nominal ?? 0,
                'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
                'lampiran' => $lampiranPath,
                'status' => 'pending',
                'created_by' => Auth::id(),
            ]);

            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('success', '✅ Pengajuan berhasil dibuat dan menunggu persetujuan.');

        } catch (\Exception $e) {
            Log::error('Persetujuan Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menyimpan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        try {
            $persetujuan = Persetujuan::with(['user', 'approver'])->findOrFail($id);
            return view('kepala-sekolah.persetujuan.show', compact('persetujuan'));
        } catch (\Exception $e) {
            Log::error('Persetujuan Show Error: ' . $e->getMessage());
            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('error', 'Data tidak ditemukan');
        }
    }

    public function edit($id)
    {
        try {
            $persetujuan = Persetujuan::findOrFail($id);
            
            if ($persetujuan->status !== 'pending') {
                return redirect()->route('kepala-sekolah.persetujuan.index')
                    ->with('error', '⚠️ Pengajuan dengan status "' . $persetujuan->status . '" tidak dapat diedit.');
            }

            $pengajuList = User::where('role', '!=', 'kepala_sekolah')
                ->orderBy('name')
                ->get();

            $statuses = ['pending', 'approved', 'rejected', 'revised'];
            $jenisList = ['kegiatan', 'anggaran', 'laporan', 'izin', 'lainnya'];

            return view('kepala-sekolah.persetujuan.edit', compact(
                'persetujuan', 
                'pengajuList', 
                'statuses', 
                'jenisList'
            ));
        } catch (\Exception $e) {
            Log::error('Persetujuan Edit Error: ' . $e->getMessage());
            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('error', 'Data tidak ditemukan');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $persetujuan = Persetujuan::findOrFail($id);
            
            if ($persetujuan->status !== 'pending') {
                return redirect()->route('kepala-sekolah.persetujuan.index')
                    ->with('error', '⚠️ Pengajuan dengan status "' . $persetujuan->status . '" tidak dapat diupdate.');
            }

            $validator = Validator::make($request->all(), [
                'judul' => 'required|string|max:255',
                'jenis' => 'required|string|in:kegiatan,anggaran,laporan,izin,lainnya',
                'deskripsi' => 'required|string|min:10',
                'nominal' => 'nullable|numeric|min:0',
                'tanggal_pelaksanaan' => 'nullable|date',
                'lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $lampiranPath = $persetujuan->lampiran;
            if ($request->hasFile('lampiran')) {
                if ($persetujuan->lampiran && file_exists(storage_path('app/public/' . $persetujuan->lampiran))) {
                    unlink(storage_path('app/public/' . $persetujuan->lampiran));
                }
                
                $file = $request->file('lampiran');
                $filename = time() . '_' . $file->getClientOriginalName();
                $lampiranPath = $file->storeAs('uploads/persetujuan', $filename, 'public');
            }

            $persetujuan->update([
                'judul' => $request->judul,
                'jenis' => $request->jenis,
                'deskripsi' => $request->deskripsi,
                'nominal' => $request->nominal ?? 0,
                'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
                'lampiran' => $lampiranPath,
            ]);

            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('success', '✅ Pengajuan berhasil diupdate.');

        } catch (\Exception $e) {
            Log::error('Persetujuan Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengupdate: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $persetujuan = Persetujuan::findOrFail($id);
            
            if (!in_array($persetujuan->status, ['pending', 'rejected'])) {
                return redirect()->route('kepala-sekolah.persetujuan.index')
                    ->with('error', '⚠️ Pengajuan dengan status "' . $persetujuan->status . '" tidak dapat dihapus.');
            }

            if ($persetujuan->lampiran && file_exists(storage_path('app/public/' . $persetujuan->lampiran))) {
                unlink(storage_path('app/public/' . $persetujuan->lampiran));
            }

            $persetujuan->delete();

            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('success', '🗑️ Pengajuan berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Persetujuan Destroy Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function approve(Request $request, $id)
    {
        try {
            $persetujuan = Persetujuan::findOrFail($id);
            
            if ($persetujuan->status !== 'pending') {
                return back()->with('error', '⚠️ Pengajuan ini sudah diproses sebelumnya.');
            }

            $request->validate([
                'catatan' => 'nullable|string|max:1000',
            ]);

            $persetujuan->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'catatan' => $request->catatan,
            ]);

            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('success', '✅ Pengajuan berhasil disetujui');

        } catch (\Exception $e) {
            Log::error('Persetujuan Approve Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyetujui: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $persetujuan = Persetujuan::findOrFail($id);
            
            if ($persetujuan->status !== 'pending') {
                return back()->with('error', '⚠️ Pengajuan ini sudah diproses sebelumnya.');
            }

            $request->validate([
                'catatan' => 'required|string|max:1000',
            ]);

            $persetujuan->update([
                'status' => 'rejected',
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'catatan' => $request->catatan,
            ]);

            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('success', '❌ Pengajuan ditolak');

        } catch (\Exception $e) {
            Log::error('Persetujuan Reject Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal menolak: ' . $e->getMessage());
        }
    }

    public function revise(Request $request, $id)
    {
        try {
            $persetujuan = Persetujuan::findOrFail($id);
            
            if ($persetujuan->status !== 'pending') {
                return back()->with('error', '⚠️ Pengajuan ini sudah diproses sebelumnya.');
            }

            $request->validate([
                'catatan' => 'nullable|string|max:1000',
            ]);

            $persetujuan->update([
                'status' => 'revised',
                'catatan' => $request->catatan,
            ]);

            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('success', '🔄 Pengajuan dikembalikan untuk revisi');

        } catch (\Exception $e) {
            Log::error('Persetujuan Revise Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal meminta revisi: ' . $e->getMessage());
        }
    }

    public function dashboard()
    {
        try {
            $statistik = [
                'pending' => Persetujuan::where('status', 'pending')->count(),
                'approved' => Persetujuan::where('status', 'approved')->count(),
                'rejected' => Persetujuan::where('status', 'rejected')->count(),
                'revised' => Persetujuan::where('status', 'revised')->count(),
                'total' => Persetujuan::count(),
                'total_nominal' => Persetujuan::where('status', 'approved')->sum('nominal'),
            ];

            $recent = Persetujuan::with('user')
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            return view('kepala-sekolah.persetujuan.dashboard', compact('statistik', 'recent'));

        } catch (\Exception $e) {
            Log::error('Persetujuan Dashboard Error: ' . $e->getMessage());
            return view('kepala-sekolah.persetujuan.dashboard', [
                'statistik' => [
                    'pending' => 0,
                    'approved' => 0,
                    'rejected' => 0,
                    'revised' => 0,
                    'total' => 0,
                    'total_nominal' => 0,
                ],
                'recent' => collect(),
            ]);
        }
    }

    public function print($id)
    {
        try {
            $persetujuan = Persetujuan::with(['user', 'approver'])->findOrFail($id);
            return view('kepala-sekolah.persetujuan.print', compact('persetujuan'));
        } catch (\Exception $e) {
            Log::error('Persetujuan Print Error: ' . $e->getMessage());
            return redirect()->route('kepala-sekolah.persetujuan.index')
                ->with('error', 'Data tidak ditemukan');
        }
    }

    public function bulkApprove(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:persetujuan,id',
            ]);

            $updated = Persetujuan::whereIn('id', $request->ids)
                ->where('status', 'pending')
                ->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);

            return redirect()->back()
                ->with('success', "✅ {$updated} pengajuan berhasil disetujui.");

        } catch (\Exception $e) {
            Log::error('Persetujuan BulkApprove Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

    public function bulkReject(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:persetujuan,id',
            ]);

            $updated = Persetujuan::whereIn('id', $request->ids)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected',
                    'rejected_by' => Auth::id(),
                    'rejected_at' => now(),
                ]);

            return redirect()->back()
                ->with('success', "❌ {$updated} pengajuan berhasil ditolak.");

        } catch (\Exception $e) {
            Log::error('Persetujuan BulkReject Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }
}