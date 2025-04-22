<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Tenant;

class TenantController extends Controller
{
    /* ================== USER ROUTES ================== */
    public function store(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'description'   => 'required|string',
            'documents'     => 'required|file|mimes:pdf|max:2048',
            'rental_type'   => 'required|string',
        ], [
            'business_name.required' => 'Nama usaha wajib diisi.',
            'business_name.string'   => 'Nama usaha harus berupa teks.',
            'business_name.max'      => 'Nama usaha maksimal 255 karakter.',

            'business_type.required' => 'Jenis usaha wajib diisi.',
            'business_type.string'   => 'Jenis usaha harus berupa teks.',
            'business_type.max'      => 'Jenis usaha maksimal 255 karakter.',

            'description.required'   => 'Deskripsi usaha wajib diisi.',
            'description.string'     => 'Deskripsi harus berupa teks.',

            'documents.required'     => 'Dokumen pendukung wajib diunggah.',
            'documents.file'         => 'File dokumen tidak valid.',
            'documents.mimes'        => 'Dokumen harus berupa file dengan format: PDF',
            'documents.max'          => 'Ukuran dokumen maksimal 2MB.',

            'rental_type.required'   => 'Jenis sewa wajib dipilih.',
            'rental_type.string'     => 'Jenis sewa tidak valid.',
        ]);

        // Simpan file
        $file = $request->file('documents');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents/tenant', $filename, 'public');

        // Simpan data tenant
        $tenant = Tenant::create([
            'business_name' => $request->business_name,
            'business_type' => $request->business_type,
            'description'   => $request->description,
            'rental_type'   => $request->rental_type,
            'documents'     => $filePath,
        ]);

        // Simpan ke pivot tenant_user
        $tenant->users()->attach(auth()->id(), [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('tenant.index')->with('success', 'Pengajuan tenant berhasil dikirim!');
    }

    public function create()
    {
        return view('user_staff.tenant.create');
    }
    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);

        // Hapus file dokumen jika ada
        $documentPath = public_path('uploads/' . $tenant->documents);
        if (file_exists($documentPath)) {
            unlink($documentPath);
        }
    
        // Hapus relasi user jika menggunakan pivot
        $tenant->users()->detach();
    
        // Hapus tenant
        $tenant->delete();
    
        return redirect()->route('tenant.index')->with('success', 'Pengajuan berhasil dihapus.');    }
    
        public function indexUser()
    {
        $user = Auth::user();
        $tenants = $user->tenants()->latest()->get();
        return view('user_staff.tenant.index', compact('tenants'));    
    }

    /* ================== STAFF ROUTES ================== */
    public function index()
    {
        $tenants = Tenant::with('users')->latest()->get();
        return view('user_staff.tenant.index', compact('tenants'));     
    }
    public function show($id)
    {
        $tenant = Tenant::with('users')->findOrFail($id);
        return view('user_staff.tenant.show', compact('tenant'));
    }
    public function approve($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->submission_status = 'disetujui';
        $tenant->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->submission_status = 'ditolak';
        $tenant->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak.');
    }





}
