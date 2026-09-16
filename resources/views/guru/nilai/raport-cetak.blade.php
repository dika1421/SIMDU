{{-- resources/views/guru/nilai/raport-cetak.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raport {{ $siswa->nama_lengkap ?? $siswa->user->name ?? '-' }} — {{ $tahunAjaran }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @page { size: A4; margin: 1.5cm; }
        * { box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            background: #f0f0f0;
            color: #000;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }

        .raport-wrapper {
            background: #fff;
            max-width: 21cm;
            margin: 0 auto;
            padding: 1.5cm;
            box-shadow: 0 4px 20px rgba(0,0,0,.1);
        }

        /* HEADER */
        .raport-header {
            display: flex;
            align-items: center;
            gap: 20px;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .raport-header .logo {
            width: 80px; height: 80px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid #000; border-radius: 50%;
            font-size: 30px; font-weight: 700;
        }
        .raport-header .header-text { flex: 1; text-align: center; }
        .raport-header .header-text h2 { font-size: 16px; font-weight: 700; margin: 0; letter-spacing: 1px; }
        .raport-header .header-text h1 { font-size: 20px; font-weight: 700; margin: 4px 0; letter-spacing: 2px; }
        .raport-header .header-text p { margin: 0; font-size: 11px; }

        /* TITLE */
        .raport-title { text-align: center; margin: 20px 0; }
        .raport-title h3 { font-size: 16px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 1.5px; }
        .raport-title p { margin: 4px 0 0; font-size: 12px; text-transform: uppercase; letter-spacing: .5px; }

        /* IDENTITAS */
        .identitas-table { width: 100%; margin-bottom: 16px; font-size: 12px; }
        .identitas-table td { padding: 3px 0; vertical-align: top; }
        .identitas-table td:first-child { width: 150px; font-weight: 600; }
        .identitas-table td:nth-child(2) { width: 10px; }

        /* TABEL NILAI */
        .nilai-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 11px; }
        .nilai-table th, .nilai-table td { border: 1px solid #000; padding: 6px 8px; vertical-align: middle; }
        .nilai-table thead th {
            background: #e8e8e8; font-weight: 700; text-align: center;
            text-transform: uppercase; letter-spacing: .5px; font-size: 10px;
        }
        .nilai-table tbody td { text-align: center; }
        .nilai-table tbody td.text-left { text-align: left; }
        .nilai-table tbody tr:nth-child(even) { background: #fafafa; }
        .nilai-akhir-cell { font-weight: 700; }

        /* KESIMPULAN */
        .kesimpulan-box {
            display: flex; justify-content: space-between; gap: 20px;
            margin-bottom: 20px; padding: 12px 16px;
            border: 1px solid #000; background: #f8f8f8;
        }
        .kesimpulan-item { text-align: center; }
        .kesimpulan-item .label { font-size: 10px; text-transform: uppercase; letter-spacing: .5px; font-weight: 600; color: #333; }
        .kesimpulan-item .value { font-size: 20px; font-weight: 700; margin-top: 4px; }

        /* CATATAN */
        .catatan-box { margin-bottom: 16px; padding: 10px 14px; border: 1px solid #000; min-height: 70px; }
        .catatan-box .title { font-weight: 700; font-size: 11px; margin-bottom: 6px; text-transform: uppercase; letter-spacing: .5px; }
        .catatan-box .content { font-size: 11px; font-style: italic; }

        /* TTD */
        .ttd-section { display: flex; justify-content: space-between; margin-top: 30px; font-size: 11px; }
        .ttd-box { text-align: center; width: 40%; }
        .ttd-box .ttd-space { height: 70px; }
        .ttd-box .nama {
            font-weight: 700; border-top: 1px solid #000; padding-top: 4px;
            display: inline-block; min-width: 150px;
        }

        /* ACTION BAR */
        .action-bar { max-width: 21cm; margin: 0 auto 16px; display: flex; justify-content: space-between; gap: 10px; }
        .btn-action {
            padding: 10px 24px; border-radius: 10px; border: none;
            font-weight: 600; font-size: 13px; cursor: pointer;
            display: inline-flex; align-items: center; gap: 6px;
            text-decoration: none; transition: all .2s;
        }
        .btn-back { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
        .btn-back:hover { background: #f8fafc; color: #1e293b; }
        .btn-print {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff; box-shadow: 0 4px 12px rgba(102,126,234,.3);
        }
        .btn-print:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(102,126,234,.5); color: #fff; }

        /* PRINT */
        @media print {
            body { background: #fff; padding: 0; font-size: 11px; }
            .raport-wrapper { box-shadow: none; padding: 0; max-width: 100%; }
            .no-print { display: none !important; }
            .nilai-table thead th { background: #e8e8e8 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .kesimpulan-box { background: #f8f8f8 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    <div class="action-bar no-print">
        <a href="{{ route('guru.nilai.raport') }}" class="btn-action btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button onclick="window.print()" class="btn-action btn-print">
            <i class="fas fa-print"></i> Cetak / Simpan PDF
        </button>
    </div>

    <div class="raport-wrapper">

        {{-- HEADER --}}
        <div class="raport-header">
            <div class="logo">
                <i class="fas fa-school"></i>
            </div>
            <div class="header-text">
                <h2>YAYASAN PENDIDIKAN SIM SEKOLAH</h2>
                <h1>SIM SEKOLAH</h1>
                <p>Jl. Pendidikan No. 1, Kota, Provinsi</p>
                <p>Telp: (021) 1234567 | Email: info@simsekolah.sch.id</p>
            </div>
        </div>

        {{-- TITLE --}}
        <div class="raport-title">
            <h3>Laporan Hasil Belajar Siswa</h3>
            <p>Tahun Ajaran {{ $tahunAjaran ?? '-' }} — Semester {{ ucfirst($semester ?? '-') }}</p>
        </div>

        {{-- IDENTITAS --}}
        <table class="identitas-table">
            <tr><td>Nama Siswa</td><td>:</td><td><strong>{{ $siswa->nama_lengkap ?? $siswa->user->name ?? '-' }}</strong></td></tr>
            <tr><td>NIS</td><td>:</td><td>{{ $siswa->nis ?? '-' }}</td></tr>
            <tr><td>Kelas</td><td>:</td><td>{{ $siswa->kelas->nama_kelas ?? $siswa->kelas->nama ?? '-' }}</td></tr>
            <tr><td>Jurusan</td><td>:</td><td>{{ $siswa->kelas->jurusan->nama ?? '-' }}</td></tr>
        </table>

        {{-- TABEL NILAI --}}
        <table class="nilai-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th style="text-align: left;">Mata Pelajaran</th>
                    <th style="width: 70px;">Nilai Akhir</th>
                    <th style="width: 60px;">Grade</th>
                    <th style="width: 120px;">Predikat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nilaiSiswa as $index => $n)
                    @php
                        $nilaiAkhir = $n->nilai_akhir ?? 0;
                        if ($nilaiAkhir >= 90) { $grade = 'A'; $predikat = 'Sangat Baik'; }
                        elseif ($nilaiAkhir >= 80) { $grade = 'B'; $predikat = 'Baik'; }
                        elseif ($nilaiAkhir >= 70) { $grade = 'C'; $predikat = 'Cukup'; }
                        elseif ($nilaiAkhir >= 60) { $grade = 'D'; $predikat = 'Kurang'; }
                        else { $grade = 'E'; $predikat = 'Sangat Kurang'; }
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-left">{{ $n->mapel->nama_mapel ?? $n->mapel->nama ?? '-' }}</td>
                        <td class="nilai-akhir-cell">{{ number_format($nilaiAkhir, 2) }}</td>
                        <td><strong>{{ $grade }}</strong></td>
                        <td>{{ $predikat }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 20px; font-style: italic;">
                            Belum ada data nilai untuk siswa ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($nilaiSiswa->count() > 0)
                <tfoot>
                    <tr style="background: #e8e8e8; font-weight: 700;">
                        <td colspan="2" style="text-align: right;">RATA-RATA</td>
                        <td>{{ number_format($rataRata ?? 0, 2) }}</td>
                        <td colspan="2">{{ $predikatKeseluruhan ?? '-' }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>

        {{-- KESIMPULAN --}}
        <div class="kesimpulan-box">
            <div class="kesimpulan-item">
                <div class="label">Total Mata Pelajaran</div>
                <div class="value">{{ $nilaiSiswa->count() }}</div>
            </div>
            <div class="kesimpulan-item">
                <div class="label">Nilai Rata-rata</div>
                <div class="value">{{ number_format($rataRata ?? 0, 2) }}</div>
            </div>
            <div class="kesimpulan-item">
                <div class="label">Predikat Keseluruhan</div>
                <div class="value" style="font-size: 14px;">{{ $predikatKeseluruhan ?? '-' }}</div>
            </div>
        </div>

        {{-- CATATAN --}}
        <div class="catatan-box">
            <div class="title">Catatan Wali Kelas</div>
            <div class="content">
                @if(($rataRata ?? 0) >= 75)
                    Siswa menunjukkan prestasi belajar yang baik. Pertahankan dan tingkatkan semangat belajar.
                @else
                    Siswa perlu meningkatkan prestasi belajar. Diharapkan lebih rajin dan fokus dalam belajar.
                @endif
            </div>
        </div>

        {{-- TTD --}}
        <div class="ttd-section">
            <div class="ttd-box">
                <div>Orang Tua / Wali</div>
                <div class="ttd-space"></div>
                <div class="nama">............................</div>
            </div>
            <div class="ttd-box">
                <div>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                <div style="margin-top: 4px;">Guru Mata Pelajaran</div>
                <div class="ttd-space"></div>
                <div class="nama">{{ $guru->nama ?? Auth::user()->name ?? '............................' }}</div>
            </div>
        </div>

    </div>

</body>
</html>