<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PengaturanController extends Controller
{
    /**
     * Helper: ambil setting dari database dengan fallback default.
     */
    private function getSetting(string $key, $default = null)
    {
        try {
            if (!\Schema::hasTable('settings')) {
                return $default;
            }
            $row = DB::table('settings')->where('key', $key)->first();
            if (!$row) return $default;

            $val = $row->value ?? null;
            // Coba decode JSON
            $decoded = json_decode($val, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : $val;
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Helper: simpan setting.
     */
    private function setSetting(string $key, $value): void
    {
        try {
            if (!\Schema::hasTable('settings')) return;

            $value = is_array($value) || is_object($value) ? json_encode($value) : (string) $value;

            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now(), 'created_at' => now()]
            );
        } catch (\Exception $e) {
            Log::warning('Gagal simpan setting ' . $key . ': ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan halaman pengaturan.
     */
    public function index()
    {
        $settings = [
            // Umum
            'nama_sekolah'   => $this->getSetting('nama_sekolah', 'SIM Sekolah'),
            'npsn'           => $this->getSetting('npsn', ''),
            'telepon'        => $this->getSetting('telepon', ''),
            'email'          => $this->getSetting('email', ''),
            'website'        => $this->getSetting('website', ''),
            'alamat'         => $this->getSetting('alamat', ''),
            'logo'           => $this->getSetting('logo', ''),
            'timezone'       => $this->getSetting('timezone', 'Asia/Jakarta'),
            'format_tanggal' => $this->getSetting('format_tanggal', 'd/m/Y'),

            // Notifikasi
            'notifikasi' => [
                'absensi_siswa' => (bool) $this->getSetting('notif_absensi_siswa', false),
                'absensi_guru'  => (bool) $this->getSetting('notif_absensi_guru', false),
                'pembayaran'    => (bool) $this->getSetting('notif_pembayaran', false),
                'pengumuman'    => (bool) $this->getSetting('notif_pengumuman', true),
                'whatsapp'      => (bool) $this->getSetting('notif_whatsapp', false),
            ],
            'quiet_start' => $this->getSetting('quiet_start', '22:00'),
            'quiet_end'   => $this->getSetting('quiet_end', '06:00'),

            // Keamanan
            'keamanan' => [
                'two_factor'  => (bool) $this->getSetting('two_factor', false),
                'notif_login' => (bool) $this->getSetting('notif_login', true),
            ],
            'session_timeout' => $this->getSetting('session_timeout', '30'),
        ];

        // Info backup (dummy, bisa dikembangkan)
        $backups = [];
        try {
            $backupDir = storage_path('app/backups');
            if (is_dir($backupDir)) {
                $files = glob($backupDir . '/*.sql');
                foreach ($files as $f) {
                    $backups[] = [
                        'name' => basename($f),
                        'date' => date('d M Y, H:i', filemtime($f)),
                        'size' => round(filesize($f) / 1024 / 1024, 2) . ' MB',
                    ];
                }
                usort($backups, fn($a, $b) => strcmp($b['date'], $a['date']));
            }
        } catch (\Exception $e) {
            // Abaikan
        }

        return view('administrasi.pengaturan.index', compact('settings', 'backups'));
    }

    /**
     * Update pengaturan umum.
     */
    public function updateUmum(Request $request)
    {
        $request->validate([
            'nama_sekolah'   => 'required|string|max:150',
            'npsn'           => 'nullable|string|max:20',
            'telepon'        => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:100',
            'website'        => 'nullable|url|max:150',
            'alamat'         => 'nullable|string|max:500',
            'logo'           => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'timezone'       => 'nullable|string|in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura',
            'format_tanggal' => 'nullable|string|in:d/m/Y,Y-m-d,d F Y',
        ]);

        try {
            // Upload logo
            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('logo', 'public');
                $this->setSetting('logo', $path);
            }

            $this->setSetting('nama_sekolah', $request->nama_sekolah);
            $this->setSetting('npsn', $request->npsn ?? '');
            $this->setSetting('telepon', $request->telepon ?? '');
            $this->setSetting('email', $request->email ?? '');
            $this->setSetting('website', $request->website ?? '');
            $this->setSetting('alamat', $request->alamat ?? '');
            $this->setSetting('timezone', $request->timezone ?? 'Asia/Jakarta');
            $this->setSetting('format_tanggal', $request->format_tanggal ?? 'd/m/Y');

            return redirect()->route('administrasi.pengaturan')
                ->with('success', 'Pengaturan umum berhasil disimpan.');

        } catch (\Exception $e) {
            Log::error('Gagal simpan pengaturan umum: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update pengaturan notifikasi.
     */
    public function updateNotifikasi(Request $request)
    {
        try {
            $this->setSetting('notif_absensi_siswa', $request->input('notif_absensi_siswa', '0'));
            $this->setSetting('notif_absensi_guru',  $request->input('notif_absensi_guru', '0'));
            $this->setSetting('notif_pembayaran',    $request->input('notif_pembayaran', '0'));
            $this->setSetting('notif_pengumuman',    $request->input('notif_pengumuman', '0'));
            $this->setSetting('notif_whatsapp',      $request->input('notif_whatsapp', '0'));
            $this->setSetting('quiet_start',         $request->input('quiet_start', '22:00'));
            $this->setSetting('quiet_end',           $request->input('quiet_end', '06:00'));

            return redirect()->route('administrasi.pengaturan')
                ->with('success', 'Pengaturan notifikasi berhasil disimpan.');

        } catch (\Exception $e) {
            Log::error('Gagal simpan notifikasi: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Update pengaturan keamanan (dan ganti password jika diisi).
     */
    public function updateKeamanan(Request $request)
    {
        $rules = [
            'current_password' => 'nullable|string',
            'new_password'     => 'nullable|string|min:8|confirmed',
            'two_factor'       => 'nullable|in:0,1',
            'notif_login'      => 'nullable|in:0,1',
            'session_timeout'  => 'nullable|in:15,30,60,120',
        ];

        $request->validate($rules, [
            'new_password.min'       => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        try {
            // Ganti password jika diisi
            if ($request->filled('new_password')) {
                if (!$request->filled('current_password')) {
                    return back()->with('error', 'Password lama wajib diisi untuk mengubah password.');
                }

                $user = Auth::user();
                if (!Hash::check($request->current_password, $user->password)) {
                    return back()->with('error', 'Password lama tidak sesuai.');
                }

                $user->password = Hash::make($request->new_password);
                $user->save();
            }

            // Simpan setting keamanan
            $this->setSetting('two_factor',      $request->input('two_factor', '0'));
            $this->setSetting('notif_login',     $request->input('notif_login', '0'));
            $this->setSetting('session_timeout', $request->input('session_timeout', '30'));

            $msg = 'Pengaturan keamanan berhasil disimpan.';
            if ($request->filled('new_password')) {
                $msg .= ' Password Anda telah diubah.';
            }

            return redirect()->route('administrasi.pengaturan')->with('success', $msg);

        } catch (\Exception $e) {
            Log::error('Gagal simpan keamanan: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Buat backup database (mysqldump).
     */
    public function backup()
    {
        try {
            $dbConfig = config('database.connections.' . config('database.default'));

            // Kalau bukan MySQL, kasih info
            if (config('database.default') !== 'mysql') {
                return back()->with('error', 'Fitur backup hanya mendukung MySQL saat ini.');
            }

            $backupDir = storage_path('app/backups');
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            $filename = 'backup-' . date('Y-m-d-His') . '.sql';
            $filepath = $backupDir . '/' . $filename;

            // Cek mysqldump tersedia
            $mysqldump = $this->findMysqldump();
            if (!$mysqldump) {
                return back()->with('error', 'mysqldump tidak ditemukan di server. Hubungi administrator.');
            }

            $cmd = sprintf(
                '%s --user=%s --password=%s --host=%s --port=%s %s > %s 2>&1',
                escapeshellcmd($mysqldump),
                escapeshellarg($dbConfig['username']),
                escapeshellarg($dbConfig['password']),
                escapeshellarg($dbConfig['host']),
                escapeshellarg($dbConfig['port'] ?? '3306'),
                escapeshellarg($dbConfig['database']),
                escapeshellarg($filepath)
            );

            exec($cmd, $output, $exitCode);

            if ($exitCode !== 0 || !file_exists($filepath) || filesize($filepath) < 100) {
                if (file_exists($filepath)) @unlink($filepath);
                return back()->with('error', 'Gagal membuat backup. Cek permission folder storage/app/backups.');
            }

            return back()->with('success', 'Backup berhasil dibuat: ' . $filename);

        } catch (\Exception $e) {
            Log::error('Backup error: ' . $e->getMessage());
            return back()->with('error', 'Gagal backup: ' . $e->getMessage());
        }
    }

    /**
     * Cari lokasi mysqldump.
     */
    private function findMysqldump(): ?string
    {
        $candidates = [
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            '/usr/local/mysql/bin/mysqldump',
            'mysqldump',
        ];
        foreach ($candidates as $cmd) {
            $check = @shell_exec('command -v ' . escapeshellarg($cmd) . ' 2>/dev/null');
            if (!empty(trim($check))) return trim($check);
            if (@is_executable($cmd)) return $cmd;
        }
        return null;
    }
}