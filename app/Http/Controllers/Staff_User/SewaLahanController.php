<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Rental;

class SewaLahanController extends Controller
{
    /* ================== USER ROUTES ================== */
    public function store(Request $request)
    {
        $request->validate([
            'rental_name' => 'required|string|max:255',
            'description'   => 'required|string',
            'rental_type'   => 'required|string',
            'documents'     => 'required|file|mimes:pdf|max:2048',
        ], [
            'rental_name.required' => 'Nama sewa wajib diisi.',
            'rental_name.string'   => 'Nama sewa harus berupa teks.',
            'rental_name.max'      => 'Nama sewa maksimal 255 karakter.',

            'description.required'   => 'Deskripsi sewa wajib diisi.',
            'description.string'     => 'Deskripsi harus berupa teks.',
            
            'rental_type.required'   => 'Jenis sewa wajib dipilih.',
            'rental_type.string'     => 'Jenis sewa tidak valid.',
            
            'documents.required'     => 'Dokumen pendukung wajib diunggah.',
            'documents.file'         => 'File dokumen tidak valid.',
            'documents.mimes'        => 'Dokumen harus berupa file dengan format: PDF',
            'documents.max'          => 'Ukuran dokumen maksimal 2MB.',

        ]);

        // Simpan file
        $file = $request->file('documents');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/rental', $filename, 'public');

        // Simpan data rental
        $rental = Rental::create([
            'rental_name' => $request->rental_name,
            'rental_type'   => $request->rental_type,
            'description'   => $request->description,
            'documents'     => $filePath,
        ]);

        // Simpan ke pivot rental_user
        $rental->users()->attach(auth()->id(), [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('sewa.index')->with('success', 'Pengajuan sewa lahan berhasil dikirim!');
    }

    public function create()
    {
        return view('user_staff.sewa-lahan.create');
    }

    public function destroy($id)
    {
        $rental = Rental::findOrFail($id);

        // Hapus file dokumen jika ada
        $documentPath = public_path('uploads/' . $rental->documents);
        if (file_exists($documentPath)) {
            unlink($documentPath);
        }

        // Hapus relasi user jika menggunakan pivot
        $rental->users()->detach();

        // Hapus rental
        $rental->delete();

        return redirect()->route('sewa.index')->with('success', 'Pengajuan berhasil dihapus.');    }

    public function indexUser()
    {
        $user = Auth::user();
        $rentals = $user->rentals()->latest()->get();
        return view('user_staff.sewa-lahan.index', compact('rentals'));    
    }


    /* ================== STAFF ROUTES ================== */
    public function index()
    {
        $rentals = Rental::with('users')->latest()->get();
        return view('user_staff.sewa-lahan.index', compact('rentals'));     
    }

    public function show($id)
    {
        $rental = Rental::with('users')->findOrFail($id);
        return view('user_staff.sewa-lahan.show', compact('rental'));
    }

    public function approve($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->submission_status = 'disetujui';
        $rental->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->submission_status = 'ditolak';
        $rental->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak.');
    }
}
