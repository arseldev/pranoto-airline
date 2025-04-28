<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Slider;
use App\Models\News;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $menuItems = [
                'header' => [
                    ['name' => 'Beranda', 'route' => route('home')],
                    ['name' => 'Informasi Publik', 'dropdown' => [
                        ['name' => 'Profil Bandara', 'route' => route('profilBandara')],
                        ['name' => 'Struktur Organisasi', 'route' => route('strukturOrganisasi')],
                        ['name' => 'Profil PPID BLU', 'route' => route('profilPPID')],
                        ['name' => 'Pejabat Bandara', 'route' => route('pejabatBandara')],
                        ['name' => 'SOP PPID', 'route' => route('sopPpid')],
                        ['name' => 'Pengajuan Informasi Publik', 'route' => route('pengajuanInformasiPublik')],
                        ]],
                        ['name' => 'Informasi', 'dropdown' => [
                            ['name' => 'Berita', 'route' => route('berita')],
                            ['name' => 'Laporan Keuangan', 'route' => route('laporanKeuangan')],
                    ]],
                    ['name' => 'Layanan', 'dropdown' => [
                        ['name' => 'PAS', 'route' => 'https://aptpranoto.id/website/layanan/pas_orang.html'],
                        ['name' => 'Tenant', 'route' => route('tenant')],
                        ['name' => 'Sewa Lahan', 'route' => route('sewaLahan')],
                        ['name' => 'Perijinan Usaha', 'route' => route('perijinanUsaha')],
                        ['name' => 'Pengiklanan', 'route' => route('pengiklanan')],
                        ['name' => 'Field Trip', 'route' => route('fieldTrip')],
                    ]],
                ],
            ];
            $footerSliders = Slider::where('is_visible_footer', true)->get();
            $headlineCount = News::where('is_published', true)
                          ->where('is_headline', true)
                          ->count();

            $topikUtama = $headlineCount > 0
                            ? News::where('is_published', true)
                                ->where('is_headline', true)
                                ->latest()
                                ->take(3)
                                ->get()
                            : News::where('is_published', true)
                                ->inRandomOrder()
                                ->latest()
                                ->take(3)
                                ->get();

            $view->with(compact('footerSliders', 'topikUtama', 'menuItems'));
        });
    }
}
