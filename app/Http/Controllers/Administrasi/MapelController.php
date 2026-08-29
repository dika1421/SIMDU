<?php
// app/Http/Controllers/Administrasi/MapelController.php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index()
    {
        return view('administrasi.mapel.index');
    }

    public function create()
    {
        return view('administrasi.mapel.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('administrasi.mapel.index');
    }

    public function show($id)
    {
        return view('administrasi.mapel.show');
    }

    public function edit($id)
    {
        return view('administrasi.mapel.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('administrasi.mapel.index');
    }

    public function destroy($id)
    {
        return redirect()->route('administrasi.mapel.index');
    }
}