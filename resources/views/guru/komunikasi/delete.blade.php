<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal{{ $pesan->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-3">
                    <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                    <h6 class="mt-3">Apakah Anda yakin ingin menghapus pesan ini?</h6>
                    <p class="text-muted small">
                        <strong>Subjek:</strong> {{ $pesan->subject }}<br>
                        <strong>Dari:</strong> 
                        @if($pesan->pengirim_type == 'guru')
                            {{ $pesan->pengirim->name ?? 'Guru' }}
                        @else
                            {{ $pesan->pengirim->name ?? 'Siswa' }}
                        @endif
                    </p>
                    <p class="text-danger small mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Pesan yang dihapus tidak dapat dikembalikan!
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Batal
                </button>
                <form action="{{ route('guru.komunikasi.destroy', $pesan->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>