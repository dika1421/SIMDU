<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SiswaController extends Controller
{
    /**
     * Tampilkan daftar siswa.
     */
    public function index(Request $request)
    {
        $query = Siswa::with(['user', 'kelas']);

        // Filter kelas
        if ($request->filled('kelas')) {
            $query->where('kelas_id', $request->kelas);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $siswa = $query->latest()->paginate(10)->withQueryString();

        // Statistik
        $totalSiswa   = Siswa::count();
        $siswaAktif   = Siswa::where('status', 'aktif')->count();
        $siswaLulus   = Siswa::where('status', 'lulus')->count();
        $kelas        = Kelas::orderBy('nama')->get();

        return view('administrasi.siswa.index', compact(
            'siswa',
            'totalSiswa',
            'siswaAktif',
            'siswaLulus',
            'kelas'
        ));
    }

    /**
     * Tampilkan form tambah siswa.
     */
    public function create()
    {
        $kelas = Kelas::orderBy('nama')->get();
        return view('administrasi.siswa.create', compact('kelas'));
    }

    /**
     * Simpan data siswa baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'   => 'required|string|max:255',
            'nis'            => 'required|string|max:20|unique:siswa,nis',
            'nisn'           => 'nullable|string|max:20|unique:siswa,nisn',
            'jenis_kelamin'  => 'required|in:L,P',
            'kelas_id'       => 'nullable|exists:kelas,id',
            'tanggal_lahir'  => 'nullable|date',
            'tempat_lahir'   => 'nullable|string|max:100',
            'alamat'         => 'nullable|string',
            'telepon'        => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255|unique:users,email',
            'status'         => 'required|in:aktif,nonaktif,lulus,dropout',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Buat user login
            $user = User::create([
                'name'     => $request->nama_lengkap,
                'email'    => $request->email ?? $request->nis . '@siswa.simdu.id',
                'password' => Hash::make($request->nis),
                'role'     => 'siswa',
            ]);

            // Simpan data siswa
            $data = $request->only([
                'nama_lengkap', 'nis', 'nisn', 'jenis_kelamin',
                'kelas_id', 'tanggal_lahir', 'tempat_lahir',
                'alamat', 'telepon', 'status',
            ]);
            $data['user_id'] = $user->id;

            // Upload foto
            if ($request->hasFile('foto')) {
                $data['foto'] = $request->file('foto')->store('siswa/foto', 'public');
            }

            Siswa::create($data);

            DB::commit();

            return redirect()
                ->route('administrasi.siswa.index')
                ->with('success', 'Data siswa berhasil ditambahkan. Password default: NIS siswa.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan siswa: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail siswa.
     */
    public function show($id)
    {
        $siswa = Siswa::with(['user', 'kelas'])->findOrFail($id);
        return view('administrasi.siswa.show', compact('siswa'));
    }

    /**
     * Tampilkan form edit siswa.
     */
    public function edit($id)
    {
        $siswa = Siswa::with('user')->findOrFail($id);
        $kelas = Kelas::orderBy('nama')->get();
        return view('administrasi.siswa.edit', compact('siswa', 'kelas'));
    }

    /**
     * Update data siswa.
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::with('user')->findOrFail($id);

        $request->validate([
            'nama_lengkap'   => 'required|string|max:255',
            'nis'            => 'required|string|max:20|unique:siswa,nis,' . $siswa->id,
            'nisn'           => 'nullable|string|max:20|unique:siswa,nisn,' . $siswa->id,
            'jenis_kelamin'  => 'required|in:L,P',
            'kelas_id'       => 'nullable|exists:kelas,id',
            'tanggal_lahir'  => 'nullable|date',
            'tempat_lahir'   => 'nullable|string|max:100',
            'alamat'         => 'nullable|string',
            'telepon'        => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255|unique:users,email,' . ($siswa->user->id ?? 0),
            'status'         => 'required|in:aktif,nonaktif,lulus,dropout',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Update user
            if ($siswa->user) {
                $siswa->user->update([
                    'name'  => $request->nama_lengkap,
                    'email' => $request->email ?? $siswa->user->email,
                ]);
            }

            // Update siswa
            $data = $request->only([
                'nama_lengkap', 'nis', 'nisn', 'jenis_kelamin',
                'kelas_id', 'tanggal_lahir', 'tempat_lahir',
                'alamat', 'telepon', 'status',
            ]);

            // Upload foto baru
            if ($request->hasFile('foto')) {
                if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                    Storage::disk('public')->delete($siswa->foto);
                }
                $data['foto'] = $request->file('foto')->store('siswa/foto', 'public');
            }

            $siswa->update($data);

            DB::commit();

            return redirect()
                ->route('administrasi.siswa.index')
                ->with('success', 'Data siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui siswa: ' . $e->getMessage());
        }
    }

    /**
     * Hapus data siswa.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $siswa = Siswa::with('user')->findOrFail($id);

            // Hapus foto
            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }

            $user = $siswa->user;
            $siswa->delete();

            if ($user) {
                $user->delete();
            }

            DB::commit();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data siswa berhasil dihapus.',
                ]);
            }

            return redirect()
                ->route('administrasi.siswa.index')
                ->with('success', 'Data siswa berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus siswa: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Gagal menghapus siswa: ' . $e->getMessage());
        }
    }

    /**
     * Import data siswa dari file CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');

        if (!$handle) {
            return back()->with('error', 'Gagal membuka file.');
        }

        // Skip BOM jika ada
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $header = fgetcsv($handle, 0, ',');
        if (!$header) {
            fclose($handle);
            return back()->with('error', 'File CSV kosong atau format tidak valid.');
        }

        // Normalisasi header
        $header = array_map(function ($h) {
            return strtolower(trim(str_replace([' ', '-'], '_', $h)));
        }, $header);

        $berhasil = 0;
        $gagal = 0;
        $errors = [];
        $rowNum = 1;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 0, ',')) !== false) {
                $rowNum++;

                if (count(array_filter($row)) === 0) {
                    continue;
                }

                $data = array_combine($header, array_pad($row, count($header), null));

                // Ambil field (fleksibel terhadap variasi nama kolom)
                $nama  = trim($data['nama_siswa'] ?? $data['nama_lengkap'] ?? $data['nama'] ?? '');
                $nis   = trim($data['nis'] ?? '');
                $nisn  = trim($data['nisn'] ?? '');
                $jk    = strtoupper(trim($data['jenis_kelamin'] ?? $data['jk'] ?? ''));

                if (empty($nama) || empty($nis)) {
                    $errors[] = "Baris {$rowNum}: NIS dan Nama Siswa wajib diisi.";
                    $gagal++;
                    continue;
                }

                // Cek duplikat NIS
                if (Siswa::where('nis', $nis)->exists()) {
                    $errors[] = "Baris {$rowNum}: NIS {$nis} sudah terdaftar.";
                    $gagal++;
                    continue;
                }

                // Normalisasi jenis kelamin
                if (in_array($jk, ['L', 'LAKI-LAKI', 'LAKI LAKI', 'MALE'])) {
                    $jk = 'L';
                } elseif (in_array($jk, ['P', 'PEREMPUAN', 'FEMALE'])) {
                    $jk = 'P';
                } else {
                    $jk = null;
                }

                // Buat user
                $user = User::create([
                    'name'     => $nama,
                    'email'    => $nis . '@siswa.simdu.id',
                    'password' => Hash::make($nis),
                    'role'     => 'siswa',
                ]);

                Siswa::create([
                    'user_id'        => $user->id,
                    'nama_lengkap'   => $nama,
                    'nis'            => $nis,
                    'nisn'           => $nisn ?: null,
                    'jenis_kelamin'  => $jk,
                    'status'         => 'aktif',
                ]);

                $berhasil++;
            }

            fclose($handle);

            if ($berhasil === 0) {
                DB::rollBack();
                return back()
                    ->with('warning', "Tidak ada data yang berhasil diimport. Gagal: {$gagal}")
                    ->with('import_errors', $errors);
            }

            DB::commit();

            $message = "Berhasil import {$berhasil} siswa.";
            if ($gagal > 0) {
                $message .= " Gagal: {$gagal} baris.";
            }

            return redirect()
                ->route('administrasi.siswa.index')
                ->with('success', $message)
                ->with('import_errors', $errors);

        } catch (\Exception $e) {
            DB::rollBack();
            if ($handle) fclose($handle);

            return back()
                ->with('error', 'Gagal import: ' . $e->getMessage())
                ->with('import_errors', $errors);
        }
    }

    /**
     * Download template CSV untuk import siswa.
     */
    public function downloadTemplate()
    {
        $fileName = 'template-import-siswa.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM untuk Excel

            // Header
            fputcsv($file, ['NIS', 'NISN', 'NAMA SISWA', 'JENIS KELAMIN', 'KELAS']);

            // Contoh baris
            fputcsv($file, ['232410074', '0012345678', 'Tiara Kirania Salsabila', 'P', 'XII KULINER']);
            fputcsv($file, ['232410075', '0012345679', 'Try Nazwa', 'P', 'XII KULINER']);
            fputcsv($file, ['232410076', '0012345680', 'Shereen Kayla', 'P', 'XII KULINER']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export data siswa ke file CSV.
     */
    public function export(Request $request)
    {
        $fileName = 'data-siswa-' . date('Y-m-d-His') . '.csv';

        $query = Siswa::with(['user', 'kelas']);

        // Terapkan filter yang sama seperti di index
        if ($request->filled('kelas')) {
            $query->where('kelas_id', $request->kelas);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $data = $query->latest()->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM untuk Excel

            // Header kolom
            fputcsv($file, [
                'No',
                'NIS',
                'NISN',
                'Nama Lengkap',
                'Email',
                'Jenis Kelamin',
                'Kelas',
                'Tingkat',
                'Jurusan',
                'Status',
            ]);

            foreach ($data as $i => $s) {
                fputcsv($file, [
                    $i + 1,
                    $s->nis ?? '-',
                    $s->nisn ?? '-',
                    $s->user->name ?? $s->nama_lengkap ?? '-',
                    $s->user->email ?? '-',
                    $s->jenis_kelamin == 'L' ? 'Laki-laki' : ($s->jenis_kelamin == 'P' ? 'Perempuan' : '-'),
                    $s->kelas->nama ?? '-',
                    $s->kelas->tingkat ?? '-',
                    $s->kelas->jurusan->nama ?? '-',
                    ucfirst($s->status ?? '-'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Reset password siswa ke NIS.
     */
    public function resetPassword($id)
    {
        try {
            $siswa = Siswa::with('user')->findOrFail($id);

            if (!$siswa->user) {
                return back()->with('error', 'User tidak ditemukan untuk siswa ini.');
            }

            $siswa->user->update([
                'password' => Hash::make($siswa->nis),
            ]);

            return back()->with('success', "Password siswa {$siswa->nama_lengkap} berhasil direset ke NIS: {$siswa->nis}");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal reset password: ' . $e->getMessage());
        }
    }

    /**
     * Mutasi siswa (pindah kelas).
     */
    public function mutasi(Request $request, $id)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        try {
            $siswa = Siswa::findOrFail($id);
            $siswa->update(['kelas_id' => $request->kelas_id]);

            return back()->with('success', 'Siswa berhasil dimutasi ke kelas baru.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mutasi siswa: ' . $e->getMessage());
        }
    }
}