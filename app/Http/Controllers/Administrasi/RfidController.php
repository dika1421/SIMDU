<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RfidController extends Controller
{
    /**
     * Tampilkan halaman scan RFID
     */
    public function index()
    {
        return view('administrasi.absensi.scan');
    }

    /**
     * Proses scan RFID untuk siswa
     */
    public function scanSiswa(Request $request)
    {
        try {
            $request->validate([
                'rfid' => 'required|string',
                'tanggal' => 'required|date',
            ]);

            $rfid = $request->rfid;
            $tanggal = $request->tanggal;
            
            DB::beginTransaction();
            
            // Cari siswa berdasarkan RFID
            $siswa = Siswa::with('user', 'kelas')->where('rfid', $rfid)->first();
            
            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'RFID tidak terdaftar! Silakan hubungi administrator.',
                    'data' => null
                ], 404);
            }
            
            // Cek absensi hari ini
            $absensi = Absensi::where('absensi_type', 'App\\Models\\Siswa')
                ->where('absensi_id', $siswa->id)
                ->whereDate('tanggal', $tanggal)
                ->first();
            
            // Cek apakah sudah absen
            if ($absensi) {
                return response()->json([
                    'success' => false,
                    'message' => $siswa->nama_lengkap . ' sudah melakukan absen hari ini pada pukul ' . date('H:i', strtotime($absensi->waktu_masuk)),
                    'data' => [
                        'nama' => $siswa->nama_lengkap,
                        'nis' => $siswa->nis,
                        'kelas' => $siswa->kelas->nama ?? '-',
                        'waktu_masuk' => $absensi->waktu_masuk ? date('H:i', strtotime($absensi->waktu_masuk)) : '-',
                        'status' => $absensi->status
                    ]
                ], 400);
            }
            
            // Tentukan status berdasarkan waktu
            $now = Carbon::now();
            $batasTerlambat = Carbon::createFromTime(7, 30, 0);
            $batasPulang = Carbon::createFromTime(15, 30, 0);
            
            $status = 'hadir';
            $waktu_masuk = $now->toTimeString();
            $keterangan = null;
            
            if ($now->greaterThan($batasTerlambat)) {
                $status = 'terlambat';
                $keterangan = 'Terlambat, masuk pukul ' . $now->format('H:i');
            }
            
            // Simpan absensi
            $absensi = Absensi::create([
                'absensi_type' => 'App\\Models\\Siswa',
                'absensi_id' => $siswa->id,
                'tanggal' => $tanggal,
                'status' => $status,
                'waktu_masuk' => $waktu_masuk,
                'keterangan' => $keterangan,
                'diinput_oleh' => auth()->id(),
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil!',
                'data' => [
                    'id' => $siswa->id,
                    'nis' => $siswa->nis,
                    'nama' => $siswa->nama_lengkap,
                    'kelas' => $siswa->kelas->nama ?? '-',
                    'status' => $status,
                    'waktu_masuk' => $now->format('H:i:s'),
                    'keterangan' => $keterangan
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in scanSiswa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
    
    /**
     * Proses scan RFID untuk guru
     */
    public function scanGuru(Request $request)
    {
        try {
            $request->validate([
                'rfid' => 'required|string',
                'tanggal' => 'required|date',
            ]);

            $rfid = $request->rfid;
            $tanggal = $request->tanggal;
            
            DB::beginTransaction();
            
            // Cari guru berdasarkan RFID
            $guru = Guru::with('user')->where('rfid', $rfid)->first();
            
            if (!$guru) {
                return response()->json([
                    'success' => false,
                    'message' => 'RFID tidak terdaftar! Silakan hubungi administrator.',
                    'data' => null
                ], 404);
            }
            
            // Cek absensi hari ini
            $absensi = Absensi::where('absensi_type', 'App\\Models\\Guru')
                ->where('absensi_id', $guru->id)
                ->whereDate('tanggal', $tanggal)
                ->first();
            
            // Cek apakah sudah absen
            if ($absensi) {
                return response()->json([
                    'success' => false,
                    'message' => $guru->nama_lengkap . ' sudah melakukan absen hari ini pada pukul ' . date('H:i', strtotime($absensi->waktu_masuk)),
                    'data' => [
                        'nama' => $guru->nama_lengkap,
                        'nip' => $guru->nip,
                        'waktu_masuk' => $absensi->waktu_masuk ? date('H:i', strtotime($absensi->waktu_masuk)) : '-',
                        'status' => $absensi->status
                    ]
                ], 400);
            }
            
            // Tentukan status berdasarkan waktu
            $now = Carbon::now();
            $batasTerlambat = Carbon::createFromTime(7, 0, 0);
            
            $status = 'hadir';
            $waktu_masuk = $now->toTimeString();
            $keterangan = null;
            
            if ($now->greaterThan($batasTerlambat)) {
                $status = 'terlambat';
                $keterangan = 'Terlambat, masuk pukul ' . $now->format('H:i');
            }
            
            // Simpan absensi
            $absensi = Absensi::create([
                'absensi_type' => 'App\\Models\\Guru',
                'absensi_id' => $guru->id,
                'tanggal' => $tanggal,
                'status' => $status,
                'waktu_masuk' => $waktu_masuk,
                'keterangan' => $keterangan,
                'diinput_oleh' => auth()->id(),
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil!',
                'data' => [
                    'id' => $guru->id,
                    'nip' => $guru->nip,
                    'nama' => $guru->nama_lengkap,
                    'status' => $status,
                    'waktu_masuk' => $now->format('H:i:s'),
                    'keterangan' => $keterangan
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in scanGuru: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
    
    /**
     * Get data siswa/rfid card
     */
    public function getCardInfo(Request $request)
    {
        try {
            $rfid = $request->rfid;
            $jenis = $request->jenis; // 'siswa' atau 'guru'
            
            if ($jenis == 'siswa') {
                $data = Siswa::with('user', 'kelas')->where('rfid', $rfid)->first();
                if ($data) {
                    return response()->json([
                        'success' => true,
                        'data' => [
                            'nama' => $data->nama_lengkap,
                            'nis' => $data->nis,
                            'kelas' => $data->kelas->nama ?? '-',
                            'foto' => $data->user->foto ?? null
                        ]
                    ]);
                }
            } elseif ($jenis == 'guru') {
                $data = Guru::with('user')->where('rfid', $rfid)->first();
                if ($data) {
                    return response()->json([
                        'success' => true,
                        'data' => [
                            'nama' => $data->nama_lengkap,
                            'nip' => $data->nip,
                            'foto' => $data->user->foto ?? null
                        ]
                    ]);
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'RFID tidak ditemukan'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Error in getCardInfo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}