{{-- resources/views/siswa/profil/edit.blade.php --}}
@extends('siswa.layouts.header')

@section('title', 'Edit Profil')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Edit Profil</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('siswa.profil.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $user->name) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="no_telepon" class="form-control" value="{{ old('no_telepon', $user->no_telepon) }}">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah', $siswa->nama_ayah) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu', $siswa->nama_ibu) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telepon Orang Tua</label>
                            <input type="text" name="no_telepon_orangtua" class="form-control" value="{{ old('no_telepon_orangtua', $siswa->no_telepon_orangtua) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pekerjaan Orang Tua</label>
                            <input type="text" name="pekerjaan_orangtua" class="form-control" value="{{ old('pekerjaan_orangtua', $siswa->pekerjaan_orangtua) }}">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Alamat Orang Tua</label>
                            <textarea name="alamat_orangtua" class="form-control" rows="3">{{ old('alamat_orangtua', $siswa->alamat_orangtua) }}</textarea>
                        </div>
                    </div>
                    
                    <hr>
                    <div class="text-end">
                        <a href="{{ route('siswa.profil.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection