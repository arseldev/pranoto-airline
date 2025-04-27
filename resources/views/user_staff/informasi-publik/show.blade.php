@extends('layouts.master')

@section('title')
  Detail Permintaan Informasi Publik
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Informasi Publik @endslot
    @slot('title') Detail Permintaan Informasi Publik @endslot
  @endcomponent

  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-4">Informasi Pemohon</h4>

          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" value="{{ $publicInformation->nama }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea class="form-control" rows="2" disabled>{{ $publicInformation->alamat }}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Pekerjaan</label>
            <input type="text" class="form-control" value="{{ $publicInformation->pekerjaan }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">NPWP</label>
            <input type="text" class="form-control" value="{{ $publicInformation->npwp }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">No. HP</label>
            <input type="text" class="form-control" value="{{ $publicInformation->no_hp }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" value="{{ $publicInformation->email }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal Permintaan</label>
            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($publicInformation->created_at)->format('d M Y - H:i') }}" disabled>
          </div>

          <hr>

          <h5 class="mb-3">Informasi yang Diminta</h5>

          <div class="mb-3">
            <label class="form-label">Rincian Informasi</label>
            <textarea class="form-control" rows="3" disabled>{{ $publicInformation->rincian_informasi }}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Tujuan Informasi</label>
            <textarea class="form-control" rows="3" disabled>{{ $publicInformation->tujuan_informasi }}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Cara Memperoleh</label>
            @php
              $caraMemperoleh = match($publicInformation->cara_memperoleh) {
                  'Melihat' => 'Melihat / Membaca / Mendengarkan / Mencatat',
                  'Salinan' => 'Mendapatkan copy salinan (hard copy)',
                  default => '-',
              };
            @endphp
            <input type="text" class="form-control" value="{{ $caraMemperoleh }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Cara Salinan</label>
            <input type="text" class="form-control" value="{{ $publicInformation->cara_salinan }}" disabled>
          </div>

          <hr>

          <h5 class="mb-3">Dokumen Terlampir</h5>

          <div class="mb-3 d-flex flex-column">
            <label class="form-label">KTP</label>
            @if ($publicInformation->ktp)
              <a href="{{ asset('uploads/' . $publicInformation->ktp) }}" class="btn btn-outline-primary" target="_blank">Lihat KTP</a>
            @else
              <input type="text" class="form-control" value="Tidak ada file" disabled>
            @endif
          </div>

          <div class="mb-3 d-flex flex-column">
            <label class="form-label">Surat Pertanggungjawaban</label>
            @if ($publicInformation->surat_pertanggungjawaban)
              <a href="{{ asset('uploads/' . $publicInformation->surat_pertanggungjawaban) }}" class="btn btn-outline-primary" target="_blank">Lihat Surat</a>
            @else
              <input type="text" class="form-control" value="Tidak ada file" disabled>
            @endif
          </div>

          <div class="mb-3 d-flex flex-column">
            <label class="form-label">Surat Permintaan Dari</label>
            <input type="text" class="form-control" value="{{ $publicInformation->surat_permintaan }}" disabled>
          </div>

          <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('informasiPublik.staffIndex') }}" class="btn btn-secondary">Kembali</a>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection
