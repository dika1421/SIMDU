<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class SiswaController extends Controller
{
    /**
     * Constructor - Cek permission di sini
     */
    public function __construct()
    {
        // Middleware untuk permission
        $this->middleware('permission:siswa.view')->only(['index', 'show']);
        $this->middleware('permission:siswa.create')->only(['create', 'store', 'import', 'downloadTemplate']);
        $this->middleware('permission:siswa.edit')->only(['edit', 'update', 'mutasi', 'resetPassword']);
        $this->middleware('permission:siswa.delete')->only(['destroy']);
        $this->middleware('permission:siswa.export')->only(['export']);
    }

    public function index(Request $request)
    {
        try {
            // Cek permission di controller (opsional, karena sudah di middleware)
            // if (!auth()->user()->hasPermission('siswa.view')) {
            //     abort(403, 'Anda tidak memiliki izin untuk melihat data siswa.');
            // }

            $query = Siswa::with(['user', 'kelas']);
            if (Schema::hasColumn('siswa','kelas_id') && method_exists(\App\Models\Kelas::class,'jurusan')) {
                $query = Siswa::with(['user', 'kelas.jurusan']);
            }

            if ($request->filled('kelas')) {
                $query->where('kelas_id', $request->kelas);
            }

            if ($request->filled('status') && Schema::hasColumn('siswa','status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nis', 'ILIKE', "%{$search}%");
                    if (Schema::hasColumn('siswa','nisn')) {
                        $q->orWhere('nisn', 'ILIKE', "%{$search}%");
                    }
                    if (Schema::hasColumn('siswa','nama')) {
                        $q->orWhere('nama', 'ILIKE', "%{$search}%");
                    }
                    if (Schema::hasColumn('siswa','nama_lengkap')) {
                        $q->orWhere('nama_lengkap', 'ILIKE', "%{$search}%");
                    }
                    $q->orWhereHas('user', function($u) use ($search) {
                        $u->where('name', 'ILIKE', "%{$search}%");
                    });
                });
            }

            $siswa = $query->orderBy('created_at', 'desc')->paginate(10);
            $siswa->appends($request->query());
            $kelas = Kelas::orderBy('nama_kelas')->get();

            return view('administrasi.siswa.index', compact('siswa', 'kelas'));
            
        } catch (\Exception $e) {
            Log::error('Error in siswa index: '. $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: '. $e->getMessage());
        }
    }

    public function create()
    {
        // Cek permission
        if (!auth()->user()->hasPermission('siswa.create')) {
            abort(403, 'Anda tidak memiliki izin untuk menambah siswa.');
        }

        $kelas = Kelas::orderBy('nama_kelas')->get();
        $roles = Role::all(); // Untuk pilihan role jika diperlukan
        
        return view('administrasi.siswa.create', compact('kelas', 'roles'));
    }

    public function store(Request $request)
    {
        // Cek permission
        if (!auth()->user()->hasPermission('siswa.create')) {
            abort(403, 'Anda tidak memiliki izin untuk menambah siswa.');
        }

        $rules = [
            'nis' => 'required|string|unique:siswa,nis',
            'email' => 'required|email|unique:users,email',
            'kelas_id' => 'nullable|exists:kelas,id',
        ];

        // validasi dinamis
        if (Schema::hasColumn('siswa','nama') || $request->has('nama')) $rules['nama'] = 'required|string|max:255';
        if (Schema::hasColumn('siswa','nama_lengkap') || $request->has('nama_lengkap')) $rules['nama_lengkap'] = 'required|string|max:255';
        if (Schema::hasColumn('siswa','nisn')) $rules['nisn'] = 'nullable|string|unique:siswa,nisn';

        $request->validate($rules);

        try {
            DB::beginTransaction();
            
            $nama = $request->nama ?? $request->nama_lengkap;
            $password = $request->filled('password') ? $request->password : $request->nis;

            // Data User
            $userData = [
                'name' => $nama,
                'email' => $request->email,
                'password' => Hash::make($password),
            ];
            
            // Tambahkan role_id (default: siswa)
            $roleSiswa = Role::where('name', 'siswa')->first();
            if ($roleSiswa) {
                $userData['role_id'] = $roleSiswa->id;
            }
            
            if (Schema::hasColumn('users','role')) $userData['role'] = 'siswa';
            if (Schema::hasColumn('users','status')) $userData['status'] = 'aktif';
            if (Schema::hasColumn('users','no_telepon') && $request->filled('no_telp_ortu')) {
                $userData['no_telepon'] = $request->no_telp_ortu;
            }

            $user = User::create($userData);

            // Data Siswa
            $siswaData = [
                'user_id' => $user->id,
                'nis' => $request->nis,
                'kelas_id' => $request->kelas_id,
            ];
            
            if (Schema::hasColumn('siswa','nama')) $siswaData['nama'] = $nama;
            if (Schema::hasColumn('siswa','nama_lengkap')) $siswaData['nama_lengkap'] = $nama;
            if (Schema::hasColumn('siswa','nisn')) $siswaData['nisn'] = $request->nisn ?? null;
            if (Schema::hasColumn('siswa','rfid_card')) $siswaData['rfid_card'] = $request->rfid_card ?? null;
            if (Schema::hasColumn('siswa','status')) $siswaData['status'] = 'aktif';

            // kolom optional lama, hanya isi jika ada
            foreach(['tempat_lahir','tanggal_lahir','jenis_kelamin','alamat','agama','nama_ayah','nama_ibu','no_telepon','no_telepon_orangtua','pekerjaan_orangtua','tahun_masuk'] as $col){
                if (Schema::hasColumn('siswa',$col) && $request->filled($col)) {
                    $siswaData[$col] = $request->$col;
                }
            }

            Siswa::create($siswaData);
            
            DB::commit();
            
            return redirect()
                ->route('administrasi.siswa.index')
                ->with('success', 'Siswa berhasil ditambahkan! Password: ' . $password);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error store: '.$e->getMessage());
            return back()
                ->with('error', 'Gagal: '.$e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        // Cek permission
        if (!auth()->user()->hasPermission('siswa.view')) {
            abort(403, 'Anda tidak memiliki izin untuk melihat detail siswa.');
        }

        try {
            $siswa = Siswa::with(['user', 'kelas'])->findOrFail($id);
            return view('administrasi.siswa.show', compact('siswa'));
        } catch (\Exception $e) {
            return redirect()
                ->route('administrasi.siswa.index')
                ->with('error', 'Data tidak ditemukan');
        }
    }

    public function edit($id)
    {
        // Cek permission
        if (!auth()->user()->hasPermission('siswa.edit')) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit siswa.');
        }

        $siswa = Siswa::with('user')->findOrFail($id);
        $kelas = Kelas::orderBy('nama_kelas')->get();
        
        return view('administrasi.siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        // Cek permission
        if (!auth()->user()->hasPermission('siswa.edit')) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit siswa.');
        }

        $siswa = Siswa::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            $nama = $request->nama ?? $request->nama_lengkap;
            
            // Update User
            if ($siswa->user) {
                $siswa->user->update(['name' => $nama]);
                if ($request->filled('password')) {
                    $siswa->user->update(['password' => Hash::make($request->password)]);
                }
            }
            
            // Update Siswa
            $upd = [];
            if (Schema::hasColumn('siswa','nama')) $upd['nama'] = $nama;
            if (Schema::hasColumn('siswa','nama_lengkap')) $upd['nama_lengkap'] = $nama;
            if (Schema::hasColumn('siswa','kelas_id')) $upd['kelas_id'] = $request->kelas_id;
            
            foreach(['tempat_lahir','tanggal_lahir','jenis_kelamin','alamat','agama','nama_ayah','nama_ibu','no_telepon_orangtua','pekerjaan_orangtua','status'] as $c){
                if (Schema::hasColumn('siswa',$c) && $request->filled($c)) {
                    $upd[$c] = $request->$c;
                }
            }
            
            $siswa->update($upd);
            
            DB::commit();
            
            return redirect()
                ->route('administrasi.siswa.index')
                ->with('success', 'Data siswa berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        // Cek permission
        if (!auth()->user()->hasPermission('siswa.delete')) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus siswa.');
        }

        try {
            DB::beginTransaction();
            
            $siswa = Siswa::findOrFail($id);
            $nama = $siswa->nama ?? $siswa->nama_lengkap ?? 'Siswa';
            
            if ($siswa->user) {
                $siswa->user->delete();
            }
            $siswa->delete();
            
            DB::commit();
            
            return redirect()
                ->route('administrasi.siswa.index')
                ->with('success', "Data siswa {$nama} berhasil dihapus");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function mutasi(Request $request, $id)
    {
        // Cek permission
        if (!auth()->user()->hasPermission('siswa.edit')) {
            abort(403, 'Anda tidak memiliki izin untuk melakukan mutasi siswa.');
        }

        $request->validate([
            'kelas_tujuan' => 'required|exists:kelas,id'
        ]);
        
        $siswa = Siswa::findOrFail($id);
        $kelasTujuan = Kelas::find($request->kelas_tujuan);
        $siswa->update(['kelas_id' => $request->kelas_tujuan]);
        
        return back()->with('success', "Mutasi siswa ke kelas {$kelasTujuan->nama_kelas} berhasil");
    }

    public function resetPassword($id)
    {
        // Cek permission
        if (!auth()->user()->hasPermission('siswa.edit')) {
            abort(403, 'Anda tidak memiliki izin untuk reset password siswa.');
        }

        $siswa = Siswa::findOrFail($id);
        
        if (!$siswa->user) {
            return back()->with('error', 'User tidak ditemukan');
        }
        
        $siswa->user->update(['password' => Hash::make($siswa->nis)]);
        $nama = $siswa->nama ?? $siswa->nama_lengkap ?? 'Siswa';
        
        return back()->with('success', "Password {$nama} direset ke NIS: {$siswa->nis}");
    }

    // =============================================
    // IMPORT & EXPORT
    // =============================================

    public function import(Request $request)
    {
        // Cek permission
        if (!auth()->user()->hasPermission('siswa.import')) {
            abort(403, 'Anda tidak memiliki izin untuk import siswa.');
        }

        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120'
        ]);
        
        $file = $request->file('file');
        $success = 0;
        $failed = 0;
        $errors = [];
        
        $handle = fopen($file->getPathname(), 'r');
        $rowNum = 0;
        
        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 0, ',')) !== false) {
                $rowNum++;
                if ($rowNum == 1) continue;
                if (count(array_filter($row)) < 2) continue;

                // Support 2 format
                $nis = '';
                $nama = '';
                $kelasNama = '';
                $nisn = '';
                
                if (count($row) >= 4 && !empty($row[0]) && !empty($row[2]) && !empty($row[3]) && is_numeric($row[0])) {
                    // Format lama NO,NISN,NIS,NAMA
                    $nisn = trim($row[1] ?? '');
                    $nis = trim($row[2] ?? '');
                    $nama = trim($row[3] ?? '');
                } else {
                    // Format baru nis,nama,kelas,rfid
                    $nis = trim($row[0] ?? '');
                    $nama = trim($row[1] ?? '');
                    $kelasNama = trim($row[2] ?? '');
                }
                
                $nis = str_replace('.0', '', $nis);
                
                if (empty($nis) || empty($nama)) {
                    $failed++;
                    $errors[] = "Baris {$rowNum}: NIS/Nama kosong";
                    continue;
                }
                
                if (Siswa::where('nis', $nis)->exists()) {
                    $failed++;
                    $errors[] = "Baris {$rowNum}: NIS {$nis} sudah ada";
                    continue;
                }
                
                if (!empty($nisn) && Schema::hasColumn('siswa','nisn') && Siswa::where('nisn', $nisn)->exists()) {
                    $failed++;
                    $errors[] = "Baris {$rowNum}: NISN {$nisn} sudah ada";
                    continue;
                }

                // Buat User
                $email = $nis . '@siswa.simdu.sch.id';
                if (User::where('email', $email)->exists()) {
                    $email = $nis . '_' . time() . rand(1,9) . '@siswa.simdu.sch.id';
                }
                
                // Cari role siswa
                $roleSiswa = Role::where('name', 'siswa')->first();
                
                $userData = [
                    'name' => $nama,
                    'email' => $email,
                    'password' => Hash::make($nis),
                ];
                
                if ($roleSiswa) {
                    $userData['role_id'] = $roleSiswa->id;
                }
                
                $user = User::create($userData);

                // Buat Siswa
                $create = [
                    'user_id' => $user->id,
                    'nis' => $nis,
                ];
                
                if (Schema::hasColumn('siswa','nama')) $create['nama'] = $nama;
                if (Schema::hasColumn('siswa','nama_lengkap')) $create['nama_lengkap'] = $nama;
                if (Schema::hasColumn('siswa','nisn')) $create['nisn'] = $nisn ?: null;
                
                if (!empty($kelasNama)) {
                    $k = Kelas::where('nama_kelas', 'ILIKE', "%{$kelasNama}%")->first();
                    if ($k) $create['kelas_id'] = $k->id;
                }
                
                Siswa::create($create);
                $success++;
            }
            
            fclose($handle);
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            if (is_resource($handle)) fclose($handle);
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
        
        $msg = "Import: {$success} berhasil, {$failed} gagal";
        
        if ($failed > 0) {
            return redirect()
                ->route('administrasi.siswa.index')
                ->with('warning', $msg)
                ->with('import_errors', $errors);
        }
        
        return redirect()
            ->route('administrasi.siswa.index')
            ->with('success', $msg);
    }

    public function downloadTemplate()
    {
        // Cek permission
        if (!auth()->user()->hasPermission('siswa.import')) {
            abort(403, 'Anda tidak memiliki izin untuk download template.');
        }

        $headers = ['nis', 'nama', 'kelas', 'rfid'];
        $data = [
            ['2526027', 'Abdul Rahman Al Hafiz', 'X A PEMASARAN', ''],
            ['2526029', 'Achmad Guntur Anggara', 'X A PEMASARAN', ''],
        ];
        
        $callback = function() use ($headers, $data) {
            $h = fopen('php://output', 'w');
            fwrite($h, "\xEF\xBB\xBF");
            fputcsv($h, $headers);
            foreach ($data as $r) {
                fputcsv($h, $r);
            }
            fclose($h);
        };
        
        return response()->streamDownload(
            $callback,
            'template_siswa.csv',
            ['Content-Type' => 'text/csv']
        );
    }

    public function export(Request $request)
    {
        // Cek permission
        if (!auth()->user()->hasPermission('siswa.export')) {
            abort(403, 'Anda tidak memiliki izin untuk export data siswa.');
        }

        $siswa = Siswa::with('kelas')->orderBy('nis')->get();
        $headers = ['NO', 'NIS', 'NAMA', 'KELAS'];
        
        $callback = function() use ($headers, $siswa) {
            $h = fopen('php://output', 'w');
            fwrite($h, "\xEF\xBB\xBF");
            fputcsv($h, $headers);
            $no = 1;
            foreach ($siswa as $s) {
                fputcsv($h, [
                    $no++,
                    $s->nis,
                    $s->nama ?? $s->nama_lengkap ?? '-',
                    $s->kelas->nama_kelas ?? '-'
                ]);
            }
            fclose($h);
        };
        
        return response()->streamDownload(
            $callback,
            'data_siswa_' . date('Y-m-d') . '.csv',
            ['Content-Type' => 'text/csv']
        );
    }
}