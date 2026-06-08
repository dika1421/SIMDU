@extends('administrasi.layouts.header')

@section('title', 'Input Absensi Guru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chalkboard-user me-2"></i>
        Input Absensi Guru
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.absensi.rekap-guru') }}" class="btn btn-sm btn-info">
            <i class="fas fa-chart-line"></i> Rekap Absensi
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" onchange="this.form.submit()">
            </div>
        </form>

        <form action="{{ route('administrasi.absensi.store-guru') }}" method="POST">
            @csrf
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">NIP</th>
                            <th width="25%">Nama Guru</th>
                            <th width="20%">Mata Pelajaran</th>
                            <th width="15%">Status</th>
                            <th width="20%">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($guru as $index => $g)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $g->nip ?? '-' }}</td>
                            <td>
                                <strong>{{ $g->nama_lengkap ?? '-' }}</strong>
                                <br>
                                <small class="text-muted">{{ $g->user->email ?? '-' }}</small>
                            </td>
                            <td>{{ $g->mata_pelajaran_utama ?? '-' }}</td>
                            <td>
                                <select name="absensi[{{ $g->id }}][status]" class="form-select">
                                    <option value="">-- Pilih Status --</option>
                                    @foreach($statusList as $key => $label)
                                        <option value="{{ $key }}" {{ $g->absensi_hari_ini && $g->absensi_hari_ini->status == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="absensi[{{ $g->id }}][keterangan]" class="form-control" 
                                       value="{{ $g->absensi_hari_ini->keterangan ?? '' }}" placeholder="Keterangan">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Absensi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection