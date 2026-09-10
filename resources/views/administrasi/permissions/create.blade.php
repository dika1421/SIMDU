{{-- resources/views/administrasi/permissions/create.blade.php --}}
@extends('administrasi.layouts.header')

@section('title', 'Tambah Permission')

@section('content')
<style>
.up-perm-create {
    --up-primary: #4f46e5;
    --up-purple: #7c3aed;
    --up-success: #10b981;
    --up-info: #0ea5e9;
    --up-danger: #dc2626;
    --up-text: #1e293b;
    --up-text-muted: #64748b;
    --up-text-light: #94a3b8;
    --up-bg-soft: #f8fafc;
    --up-border: #e2e8f0;
    --up-radius: 16px;
    --up-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
    color: var(--up-text);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding-bottom: 2rem;
}
.up-perm-create * { box-sizing: border-box; }

.up-perm-create .up-page-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--up-border);
}
.up-perm-create .up-page-title {
    font-size: 1.5rem !important;
    font-weight: 800 !important;
    color: var(--up-text) !important;
    margin: 0 0 .35rem 0 !important;
    display: flex;
    align-items: center;
    gap: 10px;
}
.up-perm-create .up-page-title i { color: var(--up-primary); }
.up-perm-create .up-page-sub {
    color: var(--up-text-light) !important;
    font-size: .9rem !important;
    margin: 0 !important;
}

.up-perm-create .up-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 20px;
    font-size: .85rem;
    font-weight: 700;
    border: none;
    border-radius: 999px;
    cursor: pointer;
    text-decoration: none;
    transition: transform .2s, box-shadow .2s;
    white-space: nowrap;
    font-family: inherit;
}
.up-perm-create .up-btn-primary {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(79, 70, 229, .25);
}
.up-perm-create .up-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(79, 70, 229, .4);
    color: #fff;
}
.up-perm-create .up-btn-outline {
    background: #fff;
    color: var(--up-text-muted);
    border: 1.5px solid var(--up-border);
}
.up-perm-create .up-btn-outline:hover {
    background: var(--up-bg-soft);
    color: var(--up-text);
}

.up-perm-create .up-card {
    background: #fff !important;
    border-radius: var(--up-radius) !important;
    box-shadow: var(--up-shadow) !important;
    overflow: hidden !important;
    max-width: 800px;
    margin: 0 auto;
}
.up-perm-create .up-card-head {
    background: linear-gradient(135deg, var(--up-primary) 0%, var(--up-purple) 100%);
    color: #fff;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 10px;
}
.up-perm-create .up-card-head h5 {
    color: #fff !important;
    font-weight: 700 !important;
    font-size: 1.05rem !important;
    margin: 0 !important;
    display: flex;
    align-items: center;
    gap: 8px;
}
.up-perm-create .up-card-head small {
    color: rgba(255,255,255,.85);
    font-size: .8rem;
    display: block;
    margin-top: 4px;
}

.up-perm-create .up-card-body {
    padding: 2rem 1.75rem;
    background: #fff;
}

.up-perm-create .up-form-group {
    margin-bottom: 1.5rem;
}
.up-perm-create .up-form-label {
    display: block;
    font-size: .75rem;
    font-weight: 700;
    color: var(--up-text-muted);
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: .5rem;
}
.up-perm-create .up-form-label .req { color: var(--up-danger); }
.up-perm-create .up-form-control {
    width: 100%;
    padding: 12px 16px;
    font-size: .9rem;
    color: var(--up-text);
    background: #fff;
    border: 1.5px solid var(--up-border);
    border-radius: 10px;
    outline: none;
    transition: all .2s;
    font-family: inherit;
}
.up-perm-create .up-form-control:focus {
    border-color: var(--up-primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, .12);
}
.up-perm-create textarea.up-form-control {
    min-height: 90px;
    resize: vertical;
}
.up-perm-create .up-form-control.is-invalid {
    border-color: var(--up-danger);
}
.up-perm-create .invalid-feedback {
    color: var(--up-danger);
    font-size: .8rem;
    margin-top: 6px;
    font-weight: 500;
}
.up-perm-create .up-form-help {
    display: block;
    color: var(--up-text-light);
    font-size: .78rem;
    margin-top: 6px;
}

/* Preview box */
.up-perm-create .up-preview {
    background: linear-gradient(135deg, #eef2ff 0%, #f5f3ff 100%);
    border: 1px solid #c7d2fe;
    border-radius: 12px;
    padding: 14px 18px;
    font-size: .85rem;
    color: #4338ca;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 10px;
}
.up-perm-create .up-preview code {
    background: rgba(255,255,255,.8);
    color: #4f46e5;
    padding: 3px 10px;
    border-radius: 6px;
    font-weight: 700;
    font-size: .85rem;
    font-family: 'Courier New', monospace;
}

/* Section divider */
.up-perm-create .up-section-title {
    font-size: .8rem;
    font-weight: 700;
    color: var(--up-text-muted);
    text-transform: uppercase;
    letter-spacing: .8px;
    margin: 2rem 0 1rem 0;
    padding-bottom: .75rem;
    border-bottom: 1px dashed var(--up-border);
    display: flex;
    align-items: center;
    gap: 8px;
}
.up-perm-create .up-section-title i {
    color: var(--up-primary);
    font-size: .9rem;
}

/* Footer */
.up-perm-create .up-card-foot {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    padding: 1.25rem 1.75rem;
    border-top: 1px solid var(--up-border);
    background: var(--up-bg-soft);
}

/* Helper chips */
.up-perm-create .up-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 8px;
}
.up-perm-create .up-chip {
    display: inline-block;
    padding: 4px 10px;
    background: #f1f5f9;
    color: var(--up-text-muted);
    border: 1px solid var(--up-border);
    border-radius: 999px;
    font-size: .72rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .15s;
    font-family: 'Courier New', monospace;
}
.up-perm-create .up-chip:hover {
    background: #eef2ff;
    color: var(--up-primary);
    border-color: var(--up-primary);
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .up-perm-create .up-card-foot {
        flex-direction: column;
    }
    .up-perm-create .up-card-foot .up-btn {
        width: 100%;
    }
}
</style>

<div class="up-perm-create">

    {{-- Page Header --}}
    <div class="up-page-head">
        <div>
            <h1 class="up-page-title">
                <i class="fas fa-plus-circle"></i>
                Tambah Permission
            </h1>
            <p class="up-page-sub">Buat permission baru untuk sistem</p>
        </div>
        <a href="{{ route('administrasi.permissions.index') }}" class="up-btn up-btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Card --}}
    <div class="up-card">
        <div class="up-card-head">
            <div>
                <h5><i class="fas fa-key"></i> Form Permission</h5>
                <small>Isi semua field yang bertanda <b>*</b></small>
            </div>
        </div>

        <div class="up-card-body">
            <form action="{{ route('administrasi.permissions.store') }}" method="POST" id="permForm">
                @csrf

                {{-- Preview --}}
                <div class="up-preview">
                    <i class="fas fa-eye"></i>
                    <div>
                        Preview nama permission:
                        <code id="permPreview">{{ old('name', 'module.action') }}</code>
                    </div>
                </div>

                {{-- Section 1: Identitas --}}
                <div class="up-section-title">
                    <i class="fas fa-info-circle"></i>
                    Identitas Permission
                </div>

                <div class="up-form-group">
                    <label for="name" class="up-form-label">
                        Nama Permission <span class="req">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           class="up-form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="Contoh: siswa.view, siswa.create, guru.edit"
                           required
                           autocomplete="off">
                    <small class="up-form-help">
                        Format: <code>module.action</code> — gunakan huruf kecil & titik.
                    </small>
                    <div class="up-chips">
                        <span class="up-chip" onclick="setName('view')">view</span>
                        <span class="up-chip" onclick="setName('create')">create</span>
                        <span class="up-chip" onclick="setName('edit')">edit</span>
                        <span class="up-chip" onclick="setName('delete')">delete</span>
                        <span class="up-chip" onclick="setName('export')">export</span>
                        <span class="up-chip" onclick="setName('import')">import</span>
                    </div>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="up-form-group">
                    <label for="display_name" class="up-form-label">
                        Nama Tampilan <span class="req">*</span>
                    </label>
                    <input type="text"
                           name="display_name"
                           id="display_name"
                           class="up-form-control @error('display_name') is-invalid @enderror"
                           value="{{ old('display_name') }}"
                           placeholder="Contoh: Lihat Siswa, Tambah Guru"
                           required
                           autocomplete="off">
                    <small class="up-form-help">Nama yang akan ditampilkan di antarmuka.</small>
                    @error('display_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Section 2: Group --}}
                <div class="up-section-title">
                    <i class="fas fa-folder"></i>
                    Group & Deskripsi
                </div>

                <div class="up-form-group">
                    <label for="group" class="up-form-label">
                        Group <span class="req">*</span>
                    </label>
                    <input type="text"
                           name="group"
                           id="group"
                           class="up-form-control @error('group') is-invalid @enderror"
                           value="{{ old('group') }}"
                           placeholder="Contoh: Siswa, Guru, Keuangan, Absensi"
                           required
                           autocomplete="off">
                    <small class="up-form-help">Digunakan untuk mengelompokkan permission di form Role.</small>
                    <div class="up-chips">
                        <span class="up-chip" onclick="setGroup('Siswa')">Siswa</span>
                        <span class="up-chip" onclick="setGroup('Guru')">Guru</span>
                        <span class="up-chip" onclick="setGroup('Kelas')">Kelas</span>
                        <span class="up-chip" onclick="setGroup('Jurusan')">Jurusan</span>
                        <span class="up-chip" onclick="setGroup('Keuangan')">Keuangan</span>
                        <span class="up-chip" onclick="setGroup('Absensi')">Absensi</span>
                        <span class="up-chip" onclick="setGroup('Laporan')">Laporan</span>
                        <span class="up-chip" onclick="setGroup('Pengaturan')">Pengaturan</span>
                    </div>
                    @error('group')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="up-form-group">
                    <label for="description" class="up-form-label">
                        Deskripsi
                    </label>
                    <textarea name="description"
                              id="description"
                              class="up-form-control @error('description') is-invalid @enderror"
                              placeholder="Deskripsi singkat permission ini (opsional)">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </form>
        </div>

        <div class="up-card-foot">
            <a href="{{ route('administrasi.permissions.index') }}" class="up-btn up-btn-outline">
                <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" form="permForm" class="up-btn up-btn-primary">
                <i class="fas fa-save"></i> Simpan Permission
            </button>
        </div>
    </div>
</div>

<script>
    // Live preview nama permission
    const nameInput = document.getElementById('name');
    const preview = document.getElementById('permPreview');
    if (nameInput && preview) {
        nameInput.addEventListener('input', function() {
            const val = this.value.trim();
            preview.textContent = val || 'module.action';
        });
    }

    // Chip: set action (append ke name)
    function setName(action) {
        if (!nameInput) return;
        let current = nameInput.value.trim();

        // Kalau ada titik, ganti action setelah titik
        if (current.includes('.')) {
            current = current.split('.')[0] + '.' + action;
        } else if (current.length > 0) {
            current = current + '.' + action;
        } else {
            current = 'module.' + action;
        }

        nameInput.value = current;
        nameInput.dispatchEvent(new Event('input'));
        nameInput.focus();
    }

    // Chip: set group
    function setGroup(groupName) {
        const groupInput = document.getElementById('group');
        if (!groupInput) return;
        groupInput.value = groupName;
        groupInput.focus();
    }
</script>
@endsection