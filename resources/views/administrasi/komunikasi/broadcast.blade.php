@extends('administrasi.layouts.header')

@section('title', 'Broadcast Pesan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-bullhorn me-2"></i>
        Broadcast Pesan
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('administrasi.komunikasi.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('administrasi.komunikasi.send-broadcast') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Judul Pesan <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Target Penerima <span class="text-danger">*</span></label>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="target[]" value="siswa" id="targetSiswa">
                            <label class="form-check-label" for="targetSiswa">
                                Siswa
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="target[]" value="guru" id="targetGuru">
                            <label class="form-check-label" for="targetGuru">
                                Guru
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="target[]" value="orang_tua" id="targetOrtu">
                            <label class="form-check-label" for="targetOrtu">
                                Orang Tua
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Isi Pesan <span class="text-danger">*</span></label>
                <textarea name="isi" class="form-control" rows="5" required></textarea>
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_penting" id="isPenting">
                    <label class="form-check-label" for="isPenting">
                        Tandai sebagai pesan penting
                    </label>
                </div>
            </div>
            
            <hr>
            
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Kirim Broadcast</button>
            </div>
        </form>
    </div>
</div>
@endsection