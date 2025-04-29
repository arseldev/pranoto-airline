@extends('layouts.laravel-default')

@section('title', 'Pejabat Bandara | APT PRANOTO')

@push('styles')
    <style>
        @media only screen and (max-width: 750px) {
            #responsible .image-responsible {
                width: 80%;
            }
        }
    </style>
@endpush

@section('content')
    <section class="container pb-5" id="responsible">
        <h2 class="fw-bold fs-1">Profil Pejabat</h2>

        {{-- Pejabat 1 --}}
        <div class="row align-items-center mb-3">
            <div class="col-md-4 text-center">
                <img src="https://aptpairport.id/wp-content/uploads/2025/01/DSC08182.png"
                    class="img-fluid rounded image-responsible" alt="Maeka Rindra Hariyanto">
            </div>
            <div class="col-md-8 mt-3 mt-md-0">
                <h4>MAEKA RINDRA HARIYANTO, SE. M.Si</h4>
                <p><strong>Jabatan:</strong> Kepala BLU Kantor UPBU Kelas I A.P.T. Pranoto</p>
                <p>
                    Pernah menjabat sebagai Plt. Kepala Seksi Angkutan Udara Dishub Kaltim (2012–2014), berbagai posisi
                    struktural lainnya, dan saat ini menjabat Kepala Kantor UPBU A.P.T. Pranoto (2023–Sekarang). Pendidikan
                    terakhir S2 Magister Ekonomi Universitas Mulawarman. Penghargaan: Satya Lancana Karya Satya 10 & 20
                    Tahun.
                </p>
            </div>
        </div>

        {{-- Pejabat 2 --}}
        <div class="row align-items-center mb-3 flex-md-row-reverse">
            <div class="col-md-4 text-center">
                <img src="https://aptpairport.id/wp-content/uploads/2025/01/DSC06491-removebg-preview.png"
                    class="img-fluid rounded image-responsible" alt="Zaldi Ardian">
            </div>
            <div class="col-md-8 mt-3 mt-md-0">
                <h4>ZALDI ARDIAN, A.Md</h4>
                <p><strong>Jabatan:</strong> Kepala Subbagian Keuangan dan Tata Usaha</p>
                <p>
                    Pernah menjabat Kepala Kantor UPBU Maratua (2020–2024), kini menjabat Kepala Subbagian Tata Usaha di
                    A.P.T. Pranoto. Latar belakang pendidikan D-III PTBL PLP Curug. Penghargaan: Satya Lancana Karya Satya
                    10 Tahun.
                </p>
            </div>
        </div>

        {{-- Pejabat 3 --}}
        <div class="row align-items-center mb-3">
            <div class="col-md-4 text-center">
                <img src="https://aptpairport.id/wp-content/uploads/2025/01/Desain-tanpa-judul-1-e1738056917715.png"
                    class="img-fluid rounded image-responsible" alt="Mochamad Ikhsan Fadilah">
            </div>
            <div class="col-md-8 mt-3 mt-md-0">
                <h4>MOCHAMAD IKHSAN FADILAH, SE, M.M.Tr</h4>
                <p><strong>Jabatan:</strong> Kepala Seksi Teknik dan Operasi</p>
                <p>
                    Pernah menjabat Kepala UPBU Yuvai Semaring (2020–2024), kini sebagai Kepala Seksi Teknik dan Operasi.
                    Pendidikan S2 di Sekolah Tinggi Manajemen Transportasi Trisakti. Penghargaan: Satya Lancana Karya Satya
                    10 Tahun.
                </p>
            </div>
        </div>

        {{-- Pejabat 4 --}}
        <div class="row align-items-center mb-3 flex-md-row-reverse">
            <div class="col-md-4 text-center">
                <img src="https://aptpairport.id/wp-content/uploads/2025/01/APT01069-removebg-preview-e1738055903513.png"
                    class="img-fluid rounded image-responsible" alt="Denny Armanto">
            </div>
            <div class="col-md-8 mt-3 mt-md-0">
                <h4>DENNY ARMANTO, S.E, M.A</h4>
                <p><strong>Jabatan:</strong> Kepala Seksi Pelayanan dan Kerjasama</p>
                <p>
                    Pernah menjabat di berbagai posisi pelayanan bandara sejak 2018, kini sebagai Kepala Seksi Pelayanan dan
                    Kerjasama. Pendidikan terakhir S2 Ilmu Administrasi. Penghargaan: Satya Lancana Karya Satya 10 Tahun.
                </p>
            </div>
        </div>

        {{-- Pejabat 5 --}}
        <div class="row align-items-center mb-3">
            <div class="col-md-4 text-center">
                <img src="https://aptpairport.id/wp-content/uploads/2025/01/m-e1738056241761.png" class="img-fluid rounded image-responsible"
                    alt="Murdoko">
            </div>
            <div class="col-md-8 mt-3 mt-md-0">
                <h4>MURDOKO, S.H.</h4>
                <p><strong>Jabatan:</strong> Kepala Seksi Keamanan Penerbangan dan Pelayanan Darurat</p>
                <p>
                    Pernah menjabat sebagai Kepala Seksi Teknik dan Keamanan (2019–2023), kini menangani Keamanan
                    Penerbangan. Pendidikan terakhir S1 Hukum Universitas Terbuka. Penghargaan: Satya Lancana Karya Satya 10
                    & 20 Tahun.
                </p>
            </div>
        </div>
    </section>
@endsection
