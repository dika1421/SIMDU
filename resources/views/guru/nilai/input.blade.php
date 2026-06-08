@extends('guru.layouts.header')

@section('title', 'Input Nilai')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>
        Input Nilai
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('guru.nilai.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            Kelas: {{ $kelas->nama_kelas ?? $kelas->nama }} | 
            Mata Pelajaran: {{ $mataPelajaran->nama_mapel }} | 
            Tahun Ajaran: {{ $request->tahun_ajaran }} | 
            Semester: {{ ucfirst($request->semester) }}
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('guru.nilai.save') }}" method="POST">
            @csrf
            <input type="hidden" name="mata_pelajaran_id" value="{{ $request->mata_pelajaran_id }}">
            <input type="hidden" name="kelas_id" value="{{ $request->kelas_id }}">
            <input type="hidden" name="tahun_ajaran" value="{{ $request->tahun_ajaran }}">
            <input type="hidden" name="semester" value="{{ $request->semester }}">
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">NIS</th>
                            <th width="15%">Nama Siswa</th>
                            <th width="10%">Nilai Harian 1</th>
                            <th width="10%">Nilai Harian 2</th>
                            <th width="10%">Nilai Harian 3</th>
                            <th width="10%">Nilai Tugas 1</th>
                            <th width="10%">Nilai Tugas 2</th>
                            <th width="10%">Nilai UTS</th>
                            <th width="10%">Nilai UAS</th>
                            <th width="10%">Nilai Praktek</th>
                            <th width="15%">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswa as $index => $s)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $s->nis ?? '-' }}</td>
                            <td>{{ $s->nama_lengkap ?? $s->user->name ?? '-' }}</td>
                            <td>
                                <input type="number" name="nilai[{{ $s->id }}][nilai_harian_1]" 
                                    class="form-control" step="0.01" min="0" max="100"
                                    value="{{ $s->nilai->nilai_harian_1 ?? '' }}">
                            </td>
                            <td>
                                <input type="number" name="nilai[{{ $s->id }}][nilai_harian_2]" 
                                    class="form-control" step="0.01" min="0" max="100"
                                    value="{{ $s->nilai->nilai_harian_2 ?? '' }}">
                            </td>
                            <td>
                                <input type="number" name="nilai[{{ $s->id }}][nilai_harian_3]" 
                                    class="form-control" step="0.01" min="0" max="100"
                                    value="{{ $s->nilai->nilai_harian_3 ?? '' }}">
                            </td>
                            <td>
                                <input type="number" name="nilai[{{ $s->id }}][nilai_tugas_1]" 
                                    class="form-control" step="0.01" min="0" max="100"
                                    value="{{ $s->nilai->nilai_tugas_1 ?? '' }}">
                            </td>
                            <td>
                                <input type="number" name="nilai[{{ $s->id }}][nilai_tugas_2]" 
                                    class="form-control" step="0.01" min="0" max="100"
                                    value="{{ $s->nilai->nilai_tugas_2 ?? '' }}">
                            </td>
                            <td>
                                <input type="number" name="nilai[{{ $s->id }}][nilai_uts]" 
                                    class="form-control" step="0.01" min="0" max="100"
                                    value="{{ $s->nilai->nilai_uts ?? '' }}">
                            </td>
                            <td>
                                <input type="number" name="nilai[{{ $s->id }}][nilai_uas]" 
                                    class="form-control" step="0.01" min="0" max="100"
                                    value="{{ $s->nilai->nilai_uas ?? '' }}">
                            </td>
                            <td>
                                <input type="number" name="nilai[{{ $s->id }}][nilai_praktek]" 
                                    class="form-control" step="0.01" min="0" max="100"
                                    value="{{ $s->nilai->nilai_praktek ?? '' }}">
                            </td>
                            <td>
                                <input type="text" name="nilai[{{ $s->id }}][catatan_guru]" 
                                    class="form-control" 
                                    value="{{ $s->nilai->catatan_guru ?? '' }}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Nilai
                </button>
            </div>
        </form>
    </div>
</div>
@endsection