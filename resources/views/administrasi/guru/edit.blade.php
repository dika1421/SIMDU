@extends('administrasi.layouts.header')

@section('title', 'Edit Guru')

@section('content')
<style>
    .form-section {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .form-section-title {
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #0d6efd;
        color: #0d6efd;
    }
    .required-field::after {
        content: " *";
        color: red;
        font-weight: bold;
    }
    select[multiple] {
        min-height: 120px;
    }
    select[multiple] option {
        padding: 6px 8px;
        border-bottom: 1px solid #eee;
    }
    select[multiple] option:checked {
        background-color: #0d6efd linear-gradient(0deg, #0d6efd, #0d6efd);
        color: white;
    }
    .badge-mapel-selected {
        background-color: #0d6efd;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        margin: 2px;
        display: inline-block;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit me-2"></i>
        Edit Guru
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.guru.index') }}" class="btn btn-sm btn-secondary">
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
        <form action="{{ route('administrasi.guru.update', $guru->id) }}" method="POST" id="formEditGuru">
            @csrf
            @method('PUT')
            
            <!-- Informasi -->
            <div class="alert alert-secondary">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Informasi:</strong>
                <ul class="mb-0 mt-2">
                    <li>Email tidak dapat diubah</li>
                    <li>Password dapat diubah melalui menu profil</li>
                </ul>
            </div>
            
            <div class="row">
                <!-- DATA PRIBADI -->
                <div class="col-12 form-section">
                    <h5 class="form-section-title">
                        <i class="fas fa-user me-2"></i> Data Pribadi
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">NAMA LENGKAP</label>
                            <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                   value="{{ old('nama_lengkap', $guru->nama_lengkap) }}" required>
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">EMAIL</label>
                            <input type="email" class="form-control" value="{{ $guru->user->email ?? '-' }}" disabled>
                            <small class="text-muted">Email tidak dapat diubah</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">JENIS KELAMIN</label>
                            <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                                <option value="P" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">TEMPAT LAHIR</label>
                            <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                                   value="{{ old('tempat_lahir', $guru->tempat_lahir) }}" required>
                            @error('tempat_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">TANGGAL LAHIR</label>
                            <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                   value="{{ old('tanggal_lahir', $guru->tanggal_lahir) }}" required>
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">AGAMA</label>
                            <select name="agama" class="form-select @error('agama') is-invalid @enderror" required>
                                <option value="">Pilih Agama</option>
                                <option value="Islam" {{ old('agama', $guru->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('agama', $guru->agama) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ old('agama', $guru->agama) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ old('agama', $guru->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ old('agama', $guru->agama) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ old('agama', $guru->agama) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                            @error('agama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label required-field">ALAMAT LENGKAP</label>
                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                                      rows="2" required>{{ old('alamat', $guru->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">NO TELEPON</label>
                            <input type="text" name="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" 
                                   value="{{ old('no_telepon', $guru->no_telepon) }}">
                            @error('no_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- DATA KEPEGAWAIAN -->
                <div class="col-12 form-section">
                    <h5 class="form-section-title">
                        <i class="fas fa-briefcase me-2"></i> Data Kepegawaian
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">NIP</label>
                            <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" 
                                   value="{{ old('nip', $guru->nip) }}" required>
                            @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">NUPTK</label>
                            <input type="text" name="nuptk" class="form-control @error('nuptk') is-invalid @enderror" 
                                   value="{{ old('nuptk', $guru->nuptk) }}">
                            <small class="text-muted">Contoh: 195875365530062</small>
                            @error('nuptk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">JABATAN</label>
                            <select name="jabatan" class="form-select @error('jabatan') is-invalid @enderror" required>
                                <option value="">Pilih Jabatan</option>
                                <option value="KEPALA SEKOLAH" {{ old('jabatan', $guru->status_kepegawaian) == 'KEPALA SEKOLAH' ? 'selected' : '' }}>KEPALA SEKOLAH</option>
                                <option value="WAKABIDKUR" {{ old('jabatan', $guru->status_kepegawaian) == 'WAKABIDKUR' ? 'selected' : '' }}>WAKABIDKUR</option>
                                <option value="KESISWAAN" {{ old('jabatan', $guru->status_kepegawaian) == 'KESISWAAN' ? 'selected' : '' }}>KESISWAAN</option>
                                <option value="KAPROG PEMASARAN" {{ old('jabatan', $guru->status_kepegawaian) == 'KAPROG PEMASARAN' ? 'selected' : '' }}>KAPROG PEMASARAN</option>
                                <option value="KAPROG TATA BOGA" {{ old('jabatan', $guru->status_kepegawaian) == 'KAPROG TATA BOGA' ? 'selected' : '' }}>KAPROG TATA BOGA</option>
                                <option value="Guru Mapel" {{ old('jabatan', $guru->status_kepegawaian) == 'Guru Mapel' ? 'selected' : '' }}>Guru Mapel</option>
                            </select>
                            @error('jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">TANGGAL MASUK</label>
                            <input type="date" name="tanggal_masuk" class="form-control @error('tanggal_masuk') is-invalid @enderror" 
                                   value="{{ old('tanggal_masuk', $guru->tmt_masuk) }}" required>
                            @error('tanggal_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">TMT SMK DARUL ULUM</label>
                            <input type="date" name="tmt" class="form-control @error('tmt') is-invalid @enderror" 
                                   value="{{ old('tmt', $guru->tmt) }}">
                            @error('tmt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">STATUS</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="aktif" {{ old('status', $guru->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $guru->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- MATA PELAJARAN Multiple Select -->
                        <div class="col-12 mb-3">
                            <label class="form-label">MATA PELAJARAN YANG DIAMPU</label>
                            <select name="mata_pelajaran[]" id="mataPelajaranSelect" class="form-select @error('mata_pelajaran') is-invalid @enderror" 
                                    multiple size="6">
                                @foreach($mataPelajaran as $mapel)
                                    <option value="{{ $mapel->id }}" 
                                        {{ in_array($mapel->id, $selectedMapel) ? 'selected' : '' }}>
                                        {{ $mapel->kode_mapel }} - {{ $mapel->nama_mapel }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Petunjuk:</strong> Tekan <kbd>Ctrl</kbd> (Windows) atau <kbd>Cmd</kbd> (Mac) untuk memilih lebih dari satu mata pelajaran
                            </small>
                            @error('mata_pelajaran')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            @if($selectedMapel && count($selectedMapel) > 0)
                                <div class="mt-2">
                                    <strong>Mata Pelajaran Terpilih Saat Ini:</strong><br>
                                    @foreach($selectedMapel as $mapelId)
                                        @php
                                            $mapel = $mataPelajaran->firstWhere('id', $mapelId);
                                        @endphp
                                        @if($mapel)
                                            <span class="badge-mapel-selected">
                                                <i class="fas fa-check-circle me-1"></i>
                                                {{ $mapel->nama_mapel }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- DATA PENDIDIKAN -->
                <div class="col-12 form-section">
                    <h5 class="form-section-title">
                        <i class="fas fa-graduation-cap me-2"></i> Data Pendidikan
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">PENDIDIKAN TERAKHIR</label>
                            <select name="pendidikan_terakhir" class="form-select @error('pendidikan_terakhir') is-invalid @enderror" required>
                                <option value="">Pilih Pendidikan Terakhir</option>
                                <option value="S3" {{ old('pendidikan_terakhir', $guru->pendidikan_terakhir) == 'S3' ? 'selected' : '' }}>S3 (Doktor)</option>
                                <option value="S2" {{ old('pendidikan_terakhir', $guru->pendidikan_terakhir) == 'S2' ? 'selected' : '' }}>S2 (Magister)</option>
                                <option value="S1" {{ old('pendidikan_terakhir', $guru->pendidikan_terakhir) == 'S1' ? 'selected' : '' }}>S1 (Sarjana)</option>
                                <option value="D4" {{ old('pendidikan_terakhir', $guru->pendidikan_terakhir) == 'D4' ? 'selected' : '' }}>D4 (Sarjana Terapan)</option>
                                <option value="D3" {{ old('pendidikan_terakhir', $guru->pendidikan_terakhir) == 'D3' ? 'selected' : '' }}>D3 (Ahli Madya)</option>
                            </select>
                            @error('pendidikan_terakhir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">NAMA UNIVERSITAS</label>
                            <input type="text" name="nama_universitas" class="form-control @error('nama_universitas') is-invalid @enderror" 
                                   value="{{ old('nama_universitas', $guru->universitas) }}" required>
                            @error('nama_universitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required-field">JURUSAN</label>
                            <input type="text" name="jurusan_pendidikan" class="form-control @error('jurusan_pendidikan') is-invalid @enderror" 
                                   value="{{ old('jurusan_pendidikan', $guru->jurusan_pendidikan) }}" required>
                            @error('jurusan_pendidikan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">TAHUN LULUS</label>
                            <select name="tahun_lulus" class="form-select @error('tahun_lulus') is-invalid @enderror">
                                <option value="">Pilih Tahun Lulus</option>
                                @for($year = date('Y'); $year >= 1980; $year--)
                                    <option value="{{ $year }}" {{ old('tahun_lulus', $guru->tahun_lulus) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                            @error('tahun_lulus')
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
                    <i class="fas fa-save"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Optional: Add validation before submit
    document.getElementById('formEditGuru')?.addEventListener('submit', function(e) {
        var selectElement = document.querySelector('select[name="mata_pelajaran[]"]');
        var selectedOptions = selectElement.selectedOptions;
        
        console.log('Selected Mata Pelajaran Count:', selectedOptions.length);
        for (var i = 0; i < selectedOptions.length; i++) {
            console.log('- ' + selectedOptions[i].text);
        }
    });
</script>
@endpush