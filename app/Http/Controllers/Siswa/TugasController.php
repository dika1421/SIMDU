<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('siswa.tugas.index');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('siswa.tugas.show', compact('id'));
    }

    /**
     * Submit tugas.
     */
    public function kumpul(Request $request, $id)
    {
        return redirect()->back()->with('success', 'Tugas berhasil dikumpulkan!');
    }

    /**
     * Batalkan pengumpulan tugas.
     */
    public function batalKumpul($id)
    {
        return redirect()->back()->with('success', 'Pengumpulan tugas dibatalkan!');
    }
}