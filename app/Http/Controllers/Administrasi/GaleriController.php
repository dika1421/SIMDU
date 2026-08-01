<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GaleriController extends Controller
{
    /**
     * Display a listing of the gallery.
     */
    public function index()
    {
        $galleries = Gallery::orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(12);
        return view('administrasi.galeri.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new gallery.
     */
    public function create()
    {
        return view('administrasi.galeri.create');
    }

    /**
     * Store a newly created gallery.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'category' => 'nullable|string|max:100',
            'event_date' => 'nullable|date',
            'status' => 'in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Upload image
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('galleries', $filename, 'public');

            Gallery::create([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $filename,
                'category' => $request->category,
                'event_date' => $request->event_date,
                'status' => $request->status ?? 'active',
                'sort_order' => $request->sort_order ?? 0,
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('administrasi.galeri.index')
                ->with('success', 'Galeri berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified gallery.
     */
    public function edit($id)
    {
        $gallery = Gallery::findOrFail($id);
        return view('administrasi.galeri.edit', compact('gallery'));
    }

    /**
     * Update the specified gallery.
     */
    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'category' => 'nullable|string|max:100',
            'event_date' => 'nullable|date',
            'status' => 'in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'event_date' => $request->event_date,
                'status' => $request->status ?? 'active',
                'sort_order' => $request->sort_order ?? 0,
            ];

            // Upload new image if exists
            if ($request->hasFile('image')) {
                // Delete old image
                if ($gallery->image && Storage::disk('public')->exists('galleries/' . $gallery->image)) {
                    Storage::disk('public')->delete('galleries/' . $gallery->image);
                }

                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('galleries', $filename, 'public');
                $data['image'] = $filename;
            }

            $gallery->update($data);

            return redirect()->route('administrasi.galeri.index')
                ->with('success', 'Galeri berhasil diupdate!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified gallery.
     */
    public function destroy($id)
    {
        try {
            $gallery = Gallery::findOrFail($id);

            // Delete image
            if ($gallery->image && Storage::disk('public')->exists('galleries/' . $gallery->image)) {
                Storage::disk('public')->delete('galleries/' . $gallery->image);
            }

            $gallery->delete();

            return redirect()->route('administrasi.galeri.index')
                ->with('success', 'Galeri berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update status gallery.
     */
    public function updateStatus(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->status = $request->status;
        $gallery->save();

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diupdate'
        ]);
    }
}