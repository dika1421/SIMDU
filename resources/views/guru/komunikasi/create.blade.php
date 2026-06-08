@extends('guru.layouts.header')

@section('title', 'Pesan Baru')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus me-2"></i>
        Pesan Baru
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('guru.komunikasi.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('guru.komunikasi.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Penerima</label>
                <select name="penerima_id[]" class="form-select @error('penerima_id') is-invalid @enderror" multiple required>
                    <option value="">Pilih Penerima</option>
                    <optgroup label="Guru">
                        @foreach($guru as $g)
                            <option value="{{ $g->id }}">{{ $g->name }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Siswa">
                        @foreach($siswa as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </optgroup>
                </select>
                @error('penerima_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Tekan Ctrl untuk memilih lebih dari satu</small>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" required>
                @error('judul')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Isi Pesan</label>
                <textarea name="isi" class="form-control @error('isi') is-invalid @enderror" rows="5" required>{{ old('isi') }}</textarea>
                @error('isi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" name="is_urgent" class="form-check-input" value="1" id="isUrgent">
                    <label class="form-check-label" for="isUrgent">
                        Tandai sebagai pesan penting
                    </label>
                </div>
            </div>
            
            <div class="text-end">
                <button type="reset" class="btn btn-secondary">Reset</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Kirim Pesan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection