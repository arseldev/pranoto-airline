<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\News;
use App\Models\Finance;
use App\Models\PublicInformation;

class LandingPageController extends Controller
{
    public function home()
    {
        $sliders = Slider::where('is_visible_home', 1)->get();

        $finances = Finance::selectRaw('
            DATE_FORMAT(MIN(date), "%M %Y") as month,
            SUM(CASE WHEN flow_type = "in" THEN amount ELSE 0 END) as pemasukan,
            SUM(CASE WHEN flow_type = "out" THEN amount ELSE 0 END) as pengeluaran
        ')
        ->groupByRaw('YEAR(date), MONTH(date)')
        ->orderByRaw('YEAR(date), MONTH(date)')
        ->get();

        // Grafik pertumbuhan keuangan
        $labels = $finances->pluck('month');
        $dataPemasukan = $finances->pluck('pemasukan');
        $dataPengeluaran = $finances->pluck('pengeluaran');

        // Pie chart total pemasukan vs pengeluaran
        $totalPemasukan = Finance::where('flow_type', 'in')->sum('amount');
        $totalPengeluaran = Finance::where('flow_type', 'out')->sum('amount');

        return view('home', compact('sliders', 'labels', 'dataPemasukan', 'dataPengeluaran', 'totalPemasukan', 'totalPengeluaran'));
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
    public function profilBandara(){return view('navigation.informasi-publik.profil-bandara.index');}
    public function pejabatBandara(){return view('navigation.informasi-publik.pejabat-bandara.index');}
    public function profilPPID(){return view('navigation.informasi-publik.profil-ppid-blu.index');}
    public function sopPpid(){return view('navigation.informasi-publik.sop-ppid.index');}
    public function pengajuanInformasiPublik(){return view('navigation.informasi-publik.pengajuan-informasi-publik.index');}
    
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
