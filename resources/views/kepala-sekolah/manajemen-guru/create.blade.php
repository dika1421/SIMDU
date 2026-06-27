@extends('kepala-sekolah.layouts.header')

@section('title', 'Tambah Guru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-plus me-2"></i>
        Tambah Guru Baru
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('kepala-sekolah.manajemen-guru.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('kepala-sekolah.manajemen-guru.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="data-pribadi-tab" data-bs-toggle="tab" data-bs-target="#data-pribadi" type="button">
                        <i class="fas fa-user me-1"></i> Data Pribadi
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="data-kepegawaian-tab" data-bs-toggle="tab" data-bs-target="#data-kepegawaian" type="button">
                        <i class="fas fa-briefcase me-1"></i> Data Kepegawaian
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="kontak-tab" data-bs-toggle="tab" data-bs-target="#kontak" type="button">
                        <i class="fas fa-address-book me-1"></i> Kontak & Alamat
                    </button>
                </li>
            </ul>
            
            <div class="tab-content">
                <!-- Tab 1: Data Pribadi -->
                <div class="tab-pane fade show active" id="data-pribadi">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap') }}" required>
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir') }}" required>
                            @error('tempat_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir') }}" required>
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                                <option value="">Pilih</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Agama <span class="text-danger">*</span></label>
                            <select name="agama" class="form-control @error('agama') is-invalid @enderror" required>
                                <option value="">Pilih</option>
                                <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                            @error('agama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Foto</label>
                            <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Tab 2: Data Kepegawaian -->
                <div class="tab-pane fade" id="data-kepegawaian">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIP <span class="text-danger">*</span></label>
                            <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}" required>
                            @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NUPTK</label>
                            <input type="text" name="nuptk" class="form-control @error('nuptk') is-invalid @enderror" value="{{ old('nuptk') }}">
                            @error('nuptk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pendidikan Terakhir <span class="text-danger">*</span></label>
                            <select name="pendidikan_terakhir" class="form-control @error('pendidikan_terakhir') is-invalid @enderror" required>
                                <option value="">Pilih</option>
                                <option value="D3" {{ old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3</option>
                                <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1</option>
                                <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2</option>
                                <option value="S3" {{ old('pendidikan_terakhir') == 'S3' ? 'selected' : '' }}>S3</option>
                            </select>
                            @error('pendidikan_terakhir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jurusan Pendidikan <span class="text-danger">*</span></label>
                            <input type="text" name="jurusan_pendidikan" class="form-control @error('jurusan_pendidikan') is-invalid @enderror" value="{{ old('jurusan_pendidikan') }}" required>
                            @error('jurusan_pendidikan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                            <input type="date" name="tmt_masuk" class="form-control @error('tmt_masuk') is-invalid @enderror" value="{{ old('tmt_masuk') }}" required>
                            @error('tmt_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status Kepegawaian <span class="text-danger">*</span></label>
                            <select name="status_kepegawaian" class="form-control @error('status_kepegawaian') is-invalid @enderror" required>
                                <option value="">Pilih</option>
                                <option value="pns" {{ old('status_kepegawaian') == 'pns' ? 'selected' : '' }}>PNS</option>
                                <option value="honorer" {{ old('status_kepegawaian') == 'honorer' ? 'selected' : '' }}>Honorer</option>
                                <option value="kontrak" {{ old('status_kepegawaian') == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                            </select>
                            @error('status_kepegawaian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                            <select name="jabatan_id" class="form-control @error('jabatan_id') is-invalid @enderror" required>
                                <option value="">Pilih</option>
                                @if(isset($jabatan) && $jabatan->count() > 0)
                                    @foreach($jabatan as $j)
                                    <option value="{{ $j->id }}" {{ old('jabatan_id') == $j->id ? 'selected' : '' }}>
                                        {{ $j->nama }}
                                    </option>
                                    @endforeach
                                @else
                                    <option value="">Belum ada data jabatan</option>
                                @endif
                            </select>
                            @error('jabatan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Tab 3: Kontak & Alamat -->
                <div class="tab-pane fade" id="kontak">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" value="{{ old('no_telepon') }}">
                            @error('no_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Golongan</label>
                            <input type="text" name="golongan" class="form-control @error('golongan') is-invalid @enderror" value="{{ old('golongan') }}">
                            @error('golongan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <hr>
            
            <div class="text-end">
                <button type="reset" class="btn btn-secondary">
                    <i class="fas fa-undo"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Data Guru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Optional: JavaScript untuk validasi tambahan
    document.addEventListener('DOMContentLoaded', function() {
        // Auto generate NIP dari tanggal masuk
        document.querySelector('input[name="tmt_masuk"]').addEventListener('change', function() {
            var tmt = this.value;
            if (tmt) {
                var year = tmt.substring(0, 4);
                var nipInput = document.querySelector('input[name="nip"]');
                if (!nipInput.value) {
                    // Generate NIP otomatis
                    nipInput.value = year + '0001';
                }
            }
        });
    });
</script>
@endpush