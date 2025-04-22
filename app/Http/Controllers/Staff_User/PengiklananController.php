<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Ad;

class PengiklananController extends Controller
{
    /* ================== USER ROUTES ================== */
    public function store(Request $request)
    {
        $request->validate([
            'ad_name' => 'required|string|max:255',
            'description'   => 'required|string',
            'ad_type'   => 'required|string',
            'documents'     => 'required|file|mimes:pdf|max:2048',
        ], [
            'ad_name.required' => 'Nama Pengiklanan wajib diisi.',
            'ad_name.string'   => 'Nama Pengiklanan harus berupa teks.',
            'ad_name.max'      => 'Nama Pengiklanan maksimal 255 karakter.',

            'description.required'   => 'Deskripsi Pengiklanan wajib diisi.',
            'description.string'     => 'Deskripsi harus berupa teks.',
            
            'ad_type.required'   => 'Jenis Pengiklanan wajib dipilih.',
            'ad_type.string'     => 'Jenis Pengiklanan tidak valid.',
            
            'documents.required'     => 'Dokumen pendukung wajib diunggah.',
            'documents.file'         => 'File dokumen tidak valid.',
            'documents.mimes'        => 'Dokumen harus berupa file dengan format: PDF',
            'documents.max'          => 'Ukuran dokumen maksimal 2MB.',

        ]);

        // Simpan file
        $file = $request->file('documents');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/ads', $filename, 'public');

        // Simpan data license
        $ad = Ad::create([
            'ad_name' => $request->ad_name,
            'ad_type'   => $request->ad_type,
            'description'   => $request->description,
            'documents'     => $filePath,
        ]);

        // Simpan ke pivot ad_user
        $ad->users()->attach(auth()->id(), [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('pengiklanan.index')->with('success', 'Pengajuan pengiklanan berhasil dikirim!');
    }

    public function create()
    {
        return view('user_staff.pengiklanan.create');
    }

    public function destroy($id)
    {
        $ad = Ad::findOrFail($id);

        $documentPath = public_path('uploads/' . $ad->documents);
        if (file_exists($documentPath)) {
            unlink($documentPath);
        }

        $ad->users()->detach();
        $ad->delete();

        return redirect()->route('pengiklanan.index')->with('success', 'Pengajuan berhasil dihapus.');    }

    public function indexUser()
    {
        $user = Auth::user();
        $ads = $user->ads()->latest()->get();
        return view('user_staff.pengiklanan.index', compact('ads'));    
    }

    /* ================== STAFF ROUTES ================== */
    public function index()
    {
        $ads = Ad::with('users')->latest()->get();
        return view('user_staff.pengiklanan.index', compact('ads'));     
    }

    public function show($id)
    {
        $ad = Ad::with('users')->findOrFail($id);
        return view('user_staff.pengiklanan.show', compact('ad'));
    }

    public function approve($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->submission_status = 'disetujui';
        $ad->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->submission_status = 'ditolak';
        $ad->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak.');
    }
}
