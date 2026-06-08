<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('kepala-sekolah.profil.index', compact('user'));
    }
    
    public function edit()
    {
        $user = Auth::user();
        return view('kepala-sekolah.profil.edit', compact('user'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_telepon' => 'nullable|string|max:20',
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
        ]);
        
        return redirect()->route('kepala-sekolah.profil.index')
            ->with('success', 'Profil berhasil diupdate!');
    }
    
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah!');
        }
        
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        return redirect()->route('kepala-sekolah.profil.index')
            ->with('success', 'Password berhasil diubah!');
    }
}