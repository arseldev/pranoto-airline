@extends('layouts.laravel-default')

@section('title', 'Pengajuan | APT PRANOTO')

@section('content')
<section class="">
  <div class="card-body">
    <h4 class="card-title mb-4 fs-2">
      @switch(request()->segment(2))
        @case('tenant')
          Syarat & Ketentuan Pengajuan Tenant
          @break
        @case('sewa-lahan')
          Syarat & Ketentuan Sewa Lahan
          @break
        @case('perijinan-usaha')
          Syarat & Ketentuan Perijinan Usaha
          @break
        @case('pengiklanan')
          Syarat & Ketentuan Pengajuan Pengiklanan
          @break
        @case('field-trip')
          Syarat & Ketentuan Field Trip
          @break
        @default
          Syarat & Ketentuan Pengajuan
      @endswitch
    </h4>

    <div class="accordion" id="accordionAjuan">

      {{-- Dokumen yang diperlukan --}}
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne" style="margin: 0; padding: 0;">
          <button class="accordion-button collapsed" style="padding-top: 5px; padding-bottom: 5px;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
            Dokumen yang Diperlukan
          </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionAjuan">
          <div class="accordion-body">
            <ul>
              @if (in_array(request()->segment(2), ['field-trip', 'pengiklanan']))
                <li>Surat Permohonan</li>
              @else
                <li>Nomor Induk Berusaha</li>
                <li>Kartu Tanda Penduduk (KTP)</li>
                <li>Akta Pendirian Perusahaan</li>
                <li>NPWP</li>
                <li>Proposal usaha</li>
                <li>Sertifikat penjamah makanan (jika F&B)</li>
                <li>Bukti bayar pajak 3 bulan terakhir</li>
                <li>Desain teknis booth/tempat usaha</li>
                <li>Surat pernyataan mengikuti aturan (bermaterai)</li>
                <li>Laporan keuangan</li>
                <li>Service Level Agreement (jika berlaku)</li>
              @endif
            </ul>
          </div>
        </div>
      </div>


      {{-- Kategori (khusus tenant) --}}
      @if (request()->segment(2) == 'tenant')
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo" style="margin: 0; padding: 0;">
          <button class="accordion-button collapsed" style="padding-top: 5px; padding-bottom: 5px;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            Kategori Tenant
          </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionAjuan">
          <div class="accordion-body">
            <ul>
              <li>Terbuka tanpa AC: Rp. 31.000/m²</li>
              <li>Tertutup tanpa AC: Rp. 48.000/m²</li>
              <li>Terbuka dengan AC: Rp. 65.000/m²</li>
              <li>Tertutup dengan AC: Rp. 82.000/m²</li>
            </ul>
          </div>
        </div>
      </div>
      @endif

      {{-- Cara Pendaftaran (sama untuk semua route) --}}
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingThree" style="margin: 0; padding: 0;">
          <button class="accordion-button collapsed" style="padding-top: 5px; padding-bottom: 5px;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            Cara Pendaftaran
          </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionAjuan">
          <div class="accordion-body">
            <ul>
              <li>Mengajukan surat permohonan kepada Kasi Pelayanan dan Kerjasama</li>
              <li>Verifikasi dokumen dan persyaratan oleh petugas pengembangan usaha</li>
              <li>Presentasi bisnis sesuai dengan bidang usaha yang diajukan</li>
              <li>Melengkapi administrasi dan kontrak jika disetujui</li>
            </ul>
          </div>
        </div>
      </div>

    </div>
    @php
      $routeSegment = request()->segment(2);
      $pengajuanRoute = match($routeSegment) {
          'tenant' => 'dashboard/tenant',
          'sewa-lahan' => 'dashboard/sewa',
          'perijinan-usaha' => 'dashboard/perijinan',
          'pengiklanan' => 'dashboard/pengiklanan',
          'field-trip' => 'dashboard/fieldtrip',
          default => '#',
      };
    @endphp

    @if ($pengajuanRoute != '#')
    <div class="d-flex justify-content-end mt-3">
      <a href="{{ url($pengajuanRoute) }}" class="btn btn-primary">
        Ajukan Sekarang
      </a>
    </div>
    @endif
  </div>
</section>
@endsection
