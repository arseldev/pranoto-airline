<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Slider;

class SliderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'slider_name' => 'required|string|max:255',
            'documents'     => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ], [
            'slider_name.required' => 'Nama slider wajib diisi.',
            'slider_name.string'   => 'Nama slider harus berupa teks.',
            'slider_name.max'      => 'Nama slider maksimal 255 karakter.',

            'documents.required'     => 'Dokumen pendukung wajib diunggah.',
            'documents.file'         => 'File dokumen tidak valid.',
            'documents.mimes'        => 'Dokumen harus berupa file dengan format: JPG/PNG',
            'documents.max'          => 'Ukuran dokumen maksimal 2MB.',

        ]);

        // Simpan file
        $file = $request->file('documents');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/slider', $filename, 'public');


        // Simpan data license
        $slider = Slider::create([
            'slider_name' => $request->slider_name,
            'documents'     => $filePath,
        ]);

        return redirect()->route('slider.index')->with('success', 'Pengajuan slider berhasil dikirim!');
    }

    public function create()
    {
        return view('user_staff.slider.create');
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);

        // Hapus file dokumen jika ada
        $documentPath = public_path('uploads/documents/slider/' . basename($slider->documents));
        if (file_exists($documentPath)) {
            unlink($documentPath);
        }

        // Hapus slider
        $slider->delete();

        return redirect()->route('slider.index')->with('success', 'Gambar berhasil dihapus.');    
    }

    public function toggleVisibilityHome(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        // Hitung jumlah slider yang sudah aktif di footer
        $activeHomeCount = Slider::where('is_visible_home', 1)->count();

        // Cek apakah ada lebih dari 3 slider yang aktif
        if ($activeHomeCount >= 3 && !$slider->is_visible_home) {
            return back()->with('error', 'Hanya 3 slider yang dapat ditampilkan di beranda.');
        }

        // Update status is_visible_footer
        $slider->is_visible_home = $request->input('is_visible_home');
        $slider->save();

        return back()->with('success', 'Status visibilitas home diperbarui.');
    }

    public function toggleVisibilityFooter(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        // Hitung jumlah slider yang sudah aktif di footer
        $activeFooterCount = Slider::where('is_visible_footer', 1)->count();

        // Cek apakah ada lebih dari 3 slider yang aktif
        if ($activeFooterCount >= 3 && !$slider->is_visible_footer) {
            return back()->with('error', 'Hanya 3 slider yang dapat ditampilkan di footer.');
        }

        // Update status is_visible_footer
        $slider->is_visible_footer = $request->input('is_visible_footer');
        $slider->save();

        return back()->with('success', 'Status visibilitas footer diperbarui.');
    }




    public function index()
    {
        $sliders = Slider::latest()->get();
        return view('user_staff.slider.index', compact('sliders'));     
    }
}
