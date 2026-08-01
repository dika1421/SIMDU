<?php
// fix-guru-login.php
// Simpan di root proyek Laravel (SIMDU)

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "========================================\n";
echo "    FIX GURU LOGIN - SIMDU\n";
echo "========================================\n\n";

// Ambil semua guru yang punya NUPTK
$gurus = Guru::whereNotNull('nuptk')->where('nuptk', '!=', '')->get();

if ($gurus->isEmpty()) {
    echo "❌ Tidak ada guru dengan NUPTK.\n";
    exit;
}

echo "📋 Menemukan " . $gurus->count() . " guru.\n\n";

$success = 0;
$failed = 0;

foreach ($gurus as $guru) {
    $user = User::find($guru->user_id);
    
    if (!$user) {
        echo "⚠️  SKIP: " . $guru->nama_lengkap . " (User tidak ditemukan)\n";
        $failed++;
        continue;
    }
    
    // Update NUPTK jika kosong
    if (empty($user->nuptk)) {
        $existing = User::where('nuptk', $guru->nuptk)->first();
        if (!$existing || $existing->id === $user->id) {
            $user->nuptk = $guru->nuptk;
            $user->save();
            echo "📝 Update NUPTK: " . $user->name . " -> " . $guru->nuptk . "\n";
        } else {
            echo "⚠️  SKIP: NUPTK " . $guru->nuptk . " sudah dipakai user lain\n";
            $failed++;
            continue;
        }
    }
    
    // Reset password default
    $password = 'simdu#3' . substr($guru->nuptk, -4);
    $user->password = Hash::make($password);
    $user->save();
    
    echo "✅ " . $guru->nama_lengkap . "\n";
    echo "   NUPTK: " . $guru->nuptk . "\n";
    echo "   Password: " . $password . "\n\n";
    
    $success++;
}

echo "========================================\n";
echo "📊 RESUME:\n";
echo "   Berhasil: " . $success . " guru\n";
echo "   Gagal: " . $failed . " guru\n";
echo "========================================\n";
echo "\n🔑 Password default: simdu#3 + 4 digit terakhir NUPTK\n";
echo "📝 Contoh: NUPTK 1652737641200012 -> password: simdu#30012\n";