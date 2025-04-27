<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Finance;
use Illuminate\Support\Facades\Validator;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter');
        $year = $request->input('year');

        // Mulai query Finance
        $finances = Finance::query();

        // Filter berdasarkan jenis aliran dana (in atau out)
        if ($filter === 'in') {
            $finances->where('flow_type', 'in');
        } elseif ($filter === 'out') {
            $finances->where('flow_type', 'out');
        }

        // Filter berdasarkan tahun (dari date)
        if ($year) {
            $finances->whereYear('date', $year);
        }

        // Ambil data dari database
        $finances = $finances->get();

        // Ambil daftar tahun yang tersedia untuk filter (dari date)
        $years = Finance::selectRaw('DISTINCT YEAR(date) as year')
                        ->whereNotNull('date')
                        ->orderBy('year', 'desc')
                        ->pluck('year');

        // Kembalikan ke view dengan data yang telah difilter
        return view('user_staff.keuangan.index', compact('finances', 'filter', 'years', 'year'));
    }

    public function create()
    {
        $finances = old('finance', []);

        $allFinance = \App\Models\Finance::select('note', 'flow_type')
            ->whereNotNull('note')
            ->get();

        $uniqueNotes = $allFinance->groupBy('flow_type')->map(function ($items) {
            return $items->pluck('note')->unique()->values();
        });

        return view('user_staff.keuangan.create', [
            'finances' => $finances,
            'uniqueNotes' => $uniqueNotes,
        ]);
    }


    public function store(Request $request)
    {
        // Validasi semua data di dalam array 'finance'
        $validator = Validator::make($request->all(), [
            'finance' => 'required|array|min:1',
            'finance.*.flow_type' => 'required|in:in,out',
            'finance.*.amount' => 'required|integer|min:1',
            'finance.*.date' => 'required|date_format:Y-m',
            'finance.*.note' => 'required|string', // Validasi catatan yang dipilih
        ], [
            'finance.required' => 'Minimal satu baris data harus diisi.',
            'finance.*.flow_type.required' => 'Aliran dana wajib diisi.',
            'finance.*.flow_type.in' => 'Aliran dana harus berupa pemasukan atau pengeluaran.',
            'finance.*.amount.required' => 'Jumlah wajib diisi.',
            'finance.*.amount.integer' => 'Jumlah harus berupa angka bulat.',
            'finance.*.amount.min' => 'Jumlah minimal adalah 1.',
            'finance.*.date.required' => 'Periode wajib diisi.',
            'finance.*.date.date_format' => 'Format periode harus YYYY-MM.',
            'finance.*.note.required' => 'Catatan wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Simpan setiap baris data
        foreach ($request->finance as $data) {
            $note = $data['note_manual'] ?? $data['note'] ?? null;

            Finance::create([
                'flow_type' => $data['flow_type'],
                'amount' => $data['amount'],
                'date' => $data['date'] . '-01', // Tambah tanggal default agar valid sebagai `date`
                'note' => $note,
            ]);
        }
        return redirect()->route('keuangan.staffIndex')->with('success', 'Data keuangan berhasil disimpan.');
    }

}
