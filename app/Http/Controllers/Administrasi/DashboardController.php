<?php

namespace App\Http\Controllers\Administrasi;  // <-- NAMESPACE HARUS INI

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('administrasi.dashboard', [
            'user' => auth()->user()
        ]);
    }
}