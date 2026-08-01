<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Tampilkan halaman landing page
     */
    public function index()
    {
        return view('landing.index');
    }

    /**
     * Tampilkan halaman tentang kami
     */
    public function about()
    {
        return view('landing.about');
    }

    /**
     * Tampilkan halaman fitur
     */
    public function features()
    {
        return view('landing.features');
    }

    /**
     * Tampilkan halaman kontak
     */
    public function contact()
    {
        return view('landing.contact');
    }
}