<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Finance;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter');
        $year = $request->input('year');

        // Mulai query Finance
        $finances = Finance::query();

        // Filter berdasarkan jenis aliran dana (in, out, atau budget)
        if ($filter === 'in') {
            $finances->where('flow_type', 'in');
        } elseif ($filter === 'out') {
            $finances->where('flow_type', 'out');
        } elseif ($filter === 'budget') {
            $finances->where('flow_type', 'budget');
        }

        // Filter berdasarkan tahun (dari date)
        if ($year) {
            $finances->whereYear('date', $year);
        }

        // Ambil data dari database dan eager load data pengeluaran terkait anggaran (jika ada)
        $finances = $finances->with('expenses')->get();

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
        // Ambil semua data finance yang masuk
        $finance = $request->input('finance');
        
        // Hitung total pengeluaran yang dimasukkan
        $budgetExpenses = $request->input('budget_expenses', []);
        $totalExpense = array_sum(array_column($budgetExpenses, 'amount'));

        // Ambil anggaran (amount) dari baris pertama finance
        $budgetAmount = $finance[0]['amount'] ?? 0;

        // Validasi jika total pengeluaran melebihi anggaran
        if ($totalExpense > $budgetAmount) {
            $errors = new MessageBag([
                'budget_expenses' => 'Total pengeluaran tidak boleh melebihi jumlah anggaran.',
            ]);

            return redirect()->back()
                ->withErrors($errors)
                ->withInput();
        }

        // Validasi semua data dalam array 'finance'
        $validator = Validator::make($request->all(), [
            'finance' => 'required|array|min:1',
            'finance.*.flow_type' => 'required|in:in,budget',
            'finance.*.amount' => 'required|integer|min:1',
            'finance.*.date' => 'required|date_format:Y-m',
            'finance.*.note' => 'nullable|string',
            'budget_expenses' => 'nullable|array',
            'budget_expenses.*.description' => 'required|string',
            'budget_expenses.*.amount' => 'required|integer|min:1',
        ], [
            'finance.required' => 'Minimal satu baris data harus diisi.',
            'finance.*.flow_type.required' => 'Aliran dana wajib diisi.',
            'finance.*.amount.required' => 'Jumlah wajib diisi.',
            'finance.*.date.required' => 'Periode wajib diisi.',
            'finance.*.note.required' => 'Catatan wajib diisi.',
            'budget_expenses.*.description.required' => 'Deskripsi pengeluaran wajib diisi.',
            'budget_expenses.*.amount.required' => 'Jumlah pengeluaran wajib diisi.',
            'budget_expenses.*.amount.min' => 'Jumlah pengeluaran minimal adalah 1.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Simpan data anggaran
        $financeRecord = Finance::create([
            'flow_type' => $finance[0]['flow_type'],
            'amount' => $finance[0]['amount'],
            'date' => $finance[0]['date'] . '-01', // Default tanggal agar valid sebagai `date`
            'note' => $finance[0]['note'] ?? null,
        ]);

        // Simpan data pengeluaran yang terkait dengan anggaran
        if ($totalExpense > 0) {
            foreach ($budgetExpenses as $expense) {
                $financeRecord->expenses()->create([
                    'description' => $expense['description'],
                    'amount' => $expense['amount'],
                ]);
            }
        }

        return redirect()->route('keuangan.staffIndex')->with('success', 'Data keuangan berhasil disimpan.');
    }



}
