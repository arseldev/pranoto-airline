<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\News;
use App\Models\Finance;
use App\Models\Visitor;
use App\Models\PublicInformation;
use Carbon\Carbon;

class LandingPageController extends Controller
{
    public function home(Request $request)
    {
        $ip = $request->ip(); // IP Address pengunjung
        $userAgent = $request->header('User-Agent'); // Informasi browser/device
        $sliders = Slider::where('is_visible_home', 1)->get();

        Visitor::create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);        

        return view('home', compact('sliders'));
    }

    public function berita()
    {
        $headlines = News::where('is_published', true)
                        ->where('is_headline', true)
                        ->latest()
                        ->first();

        if ($headlines) {
            $headline       = News::where('is_published', true)
                                ->where('is_headline', true)
                                ->latest()
                                ->first();
            $subHeadlines   = News::where('is_published', true)
                                ->where('is_headline', true)
                                ->latest()
                                ->skip(1)
                                ->take(3)
                                ->get();
        } else {
            $headline       = News::where('is_published', true)
                                ->inRandomOrder()
                                ->latest()
                                ->first();
            $subHeadlines   = News::where('is_published', true)
                                ->inRandomOrder()
                                ->take(3)
                                ->get();
        }

        $latestArticles = News::where('is_published', true)->orderBy('created_at', 'desc')->take(6)->get();
        $otherArticles = News::where('is_published', true)
                        ->orderBy('created_at', 'desc')
                        ->skip(6)
                        ->take(30)
                        ->get();

        return view('navigation.informasi.berita.index', compact('headline','subHeadlines','latestArticles', 'otherArticles'));
    }
    public function showNews($slug)
    {
        $news = News::where('slug', $slug)->where('is_published', true)->firstOrFail();

        $latestArticles = News::where('is_published', true)->orderBy('created_at', 'desc')->take(6)->get();


        return view('navigation.informasi.berita.show', compact('news', 'latestArticles'));
    }
    public function tenant(){return view('navigation.informasi.ajuan.index');}
    public function sewaLahan(){return view('navigation.informasi.ajuan.index');}
    public function perijinanUsaha(){return view('navigation.informasi.ajuan.index');}
    public function pengiklanan(){return view('navigation.informasi.ajuan.index');}
    public function fieldTrip(){return view('navigation.informasi.ajuan.index');}

    public function profilBandara(){return view('navigation.informasi-publik.profil-bandara.index');}
    public function strukturOrganisasi(){return view('navigation.informasi-publik.struktur-organisasi.index');}
    public function pejabatBandara(){return view('navigation.informasi-publik.pejabat-bandara.index');}
    public function profilPPID(){return view('navigation.informasi-publik.profil-ppid-blu.index');}
    public function sopPpid(){return view('navigation.informasi-publik.sop-ppid.index');}
    public function pengajuanInformasiPublik(){return view('navigation.informasi-publik.pengajuan-informasi-publik.index');}
    
    public function laporanKeuangan(Request $request)
    {
        // Ambil data berdasarkan filter yang dipilih
        $jenis_filter = $request->input('jenis_filter', 'bulan');
        $filterTahun = $request->input('tahun', now()->year);
        
        // Ambil data berdasarkan filter tahun yang dipilih untuk grafik pie
        $filterTahunPie = $request->input('tahun_pie', now()->year);
        
        // Ambil nilai anggaran dari request dan konversi ke integer
        $anggaran = $request->has('anggaran') ? (int)$request->input('anggaran') : null;
        
        // Debug nilai anggaran yang diterima
        // \Log::info('Nilai anggaran yang diterima: ' . $request->input('anggaran'));
        // \Log::info('Nilai anggaran setelah konversi: ' . $anggaran);
        
        // Flag untuk menentukan apakah grafik pie perlu ditampilkan
        $showPieChart = $anggaran !== null && $anggaran > 0;
        
        // Ambil semua tahun yang tersedia di database untuk dropdown
        $years = Finance::selectRaw('YEAR(date) as year')
                        ->distinct()
                        ->orderBy('year', 'desc')
                        ->get()
                        ->pluck('year');
        
        // Jika tidak ada tahun yang tersedia, gunakan tahun sekarang
        if ($years->isEmpty()) {
            $years = collect([now()->year]);
        }
        
        // Mendapatkan data pemasukan per bulan atau per tahun
        if ($jenis_filter == 'bulan') {
            // Filter per bulan dalam satu tahun tertentu
            $data = Finance::whereYear('date', $filterTahun)
                            ->where('flow_type', 'in') // hanya pemasukan
                            ->selectRaw('MONTH(date) as bulan, SUM(amount) as total_pemasukan')
                            ->groupBy('bulan')
                            ->orderBy('bulan')
                            ->get();
            
            // Untuk bulan, label bulan bisa diatur sesuai nama bulan
            $labels = $data->map(function ($item) {
                return \Carbon\Carbon::createFromFormat('m', $item->bulan)->format('F');
            });
            $dataPemasukan = $data->pluck('total_pemasukan');
        } else {
            // Filter per tahun untuk semua tahun yang ada
            $data = Finance::where('flow_type', 'in')
                            ->selectRaw('YEAR(date) as tahun, SUM(amount) as total_pemasukan')
                            ->groupBy('tahun')
                            ->orderBy('tahun')
                            ->get();
            
            // Untuk tahun, label adalah semua tahun yang ada
            $labels = $data->pluck('tahun');
            $dataPemasukan = $data->pluck('total_pemasukan');
        }
        
        // Data untuk grafik pie: anggaran vs pengeluaran dalam tahun yang dipilih
        $totalPemasukan = Finance::whereYear('date', $filterTahunPie)
                            ->where('flow_type', 'in')
                            ->sum('amount');
        
        $totalPengeluaran = $showPieChart ? Finance::whereYear('date', $filterTahunPie)
                            ->where('flow_type', 'out')
                            ->sum('amount') : 0;
        
        return view('navigation.informasi.laporan-keuangan.index', compact(
            'labels', 'dataPemasukan', 'totalPemasukan', 'totalPengeluaran', 
            'years', 'filterTahun', 'filterTahunPie', 'jenis_filter', 
            'anggaran', 'showPieChart'
        ));
    }

    public function storePengajuanInformasiPublik(Request $request)
    {
        
        $validated = $request->validate([
            
            'ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'surat_pertanggungjawaban' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'surat_permintaan' => 'required|string',

            
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
            'npwp' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'email' => 'required|email',

            'rincian_informasi' => 'required|string',
            'tujuan_informasi' => 'required|string',
            'cara_memperoleh' => 'required|string',
            'cara_salinan' => 'required|string',
        ], [
            'required' => ':attribute wajib diisi.',
            'email' => 'Format email tidak valid.',
            'file' => ':attribute harus berupa file.',
            'mimes' => ':attribute harus berupa file dengan format: :values.',
            'max' => ':attribute tidak boleh lebih dari :max kilobyte.',
        ]);

        
        $ktpPath = $request->file('ktp')->storeAs(
            'documents/pengajuan-informasi/ktp',
            time() . '_' . $request->file('ktp')->getClientOriginalName(),
            'public'
        );

        $suratPertanggungjawabanPath = $request->file('surat_pertanggungjawaban')->storeAs(
            'documents/pengajuan-informasi/surat-pertanggung-jawaban',
            time() . '_' . $request->file('surat_pertanggungjawaban')->getClientOriginalName(),
            'public'
        );

        
        PublicInformation::create([
            'ktp' => $ktpPath,
            'surat_pertanggungjawaban' => $suratPertanggungjawabanPath,
            'surat_permintaan' => $validated['surat_permintaan'],
            'nama' => $validated['nama'],
            'alamat' => $validated['alamat'],
            'pekerjaan' => $validated['pekerjaan'],
            'npwp' => $validated['npwp'],
            'no_hp' => $validated['no_hp'],
            'email' => $validated['email'],
            'rincian_informasi' => $validated['rincian_informasi'],
            'tujuan_informasi' => $validated['tujuan_informasi'],
            'cara_memperoleh' => $validated['cara_memperoleh'],
            'cara_salinan' => $validated['cara_salinan'],
        ]);

        return redirect()->back()->with('success', 'Pengajuan informasi berhasil dikirim.');
    }
}
