@extends('administrasi.layouts.header')

@section('title', 'Detail Dokumen')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-file-alt me-2"></i>
        Detail Dokumen
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.arsip.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('administrasi.arsip.download', $arsip->id) }}" class="btn btn-sm btn-success ms-2">
            <i class="fas fa-download"></i> Download
        </a>
        <a href="{{ route('administrasi.arsip.edit', $arsip->id) }}" class="btn btn-sm btn-warning ms-2">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Informasi Dokumen</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th width="200">Nomor Dokumen</th>
                <td>{{ $arsip->nomor_dokumen ?? '-' }}</td>
            </tr>
            <tr>
                <th>Nama Dokumen</th>
                <td><strong>{{ $arsip->nama_dokumen }}</strong></td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td>
                    @php
                        $kategoriLabels = [
                            'surat_keputusan' => 'Surat Keputusan',
                            'laporan_bulanan' => 'Laporan Bulanan',
                            'sertifikat' => 'Sertifikat',
                            'dokumen_siswa' => 'Dokumen Siswa',
                            'dokumen_guru' => 'Dokumen Guru',
                            'akreditasi' => 'Akreditasi',
                            'kurikulum' => 'Kurikulum',
                            'keuangan' => 'Keuangan'
                        ];
                    @endphp
                    {{ $kategoriLabels[$arsip->kategori] ?? $arsip->kategori }}
                </td>
            </tr>
            <tr>
                <th>Tanggal Dokumen</th>
                <td>{{ \Carbon\Carbon::parse($arsip->tanggal_dokumen)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Uploaded By</th>
                <td>{{ $arsip->uploader->name }} ({{ \Carbon\Carbon::parse($arsip->created_at)->format('d/m/Y H:i') }})</td>
            </tr>
            <tr>
                <th>Keterangan</th>
                <td>{{ $arsip->keterangan ?? '-' }}</td>
            </tr>
            <tr>
                <th>File</th>
                <td>
                    <a href="{{ route('administrasi.arsip.download', $arsip->id) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-download"></i> Download File
                    </a>
                </td>
            </tr>
        </table>
    </div>
</div>

@if(in_array(pathinfo($arsip->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
<div class="card mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Preview</h5>
    </div>
    <div class="card-body text-center">
        <img src="{{ asset('storage/'.$arsip->file_path) }}" class="img-fluid" style="max-height: 500px;">
    </div>
</div>
@endif
@endsection