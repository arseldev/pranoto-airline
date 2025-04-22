@extends('layouts.master')

@section('title')
  Detail Pengajuan Pengiklanan
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Pengiklanan @endslot
    @slot('title') Detail Pengajuan Pengiklanan @endslot
  @endcomponent

  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-4">Informasi Pengaju</h4>

          <div class="mb-3">
            <label class="form-label">Nama Pengaju</label>
            <input type="text" class="form-control" value="{{ $ad->users->first()?->name ?? '-' }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Email Pengaju</label>
            <input type="text" class="form-control" value="{{ $ad->users->first()?->email ?? '-' }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal Pengajuan</label>
            <input type="text" class="form-control" value="{{ $ad->created_at->format('d M Y - H:i') }}" disabled>
          </div>

          <hr>

          <h5 class="mb-3">Detail Pengiklanan</h5>

          <div class="mb-3">
            <label for="ad_name" class="form-label">Nama Pengiklanan</label>
            <input type="text" class="form-control" id="ad_name" value="{{ $ad->ad_name }}" disabled>
          </div>

          <div class="mb-3">
            <label for="ad_type" class="form-label">Jenis Pengiklanan</label>
            <input type="text" class="form-control" id="ad_type" value="{{ $ad->ad_type }}" disabled>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Deskripsi Pengiklanan</label>
            <textarea class="form-control" id="description" rows="4" disabled>{{ $ad->description }}</textarea>
          </div>

          <div class="mb-3 d-flex flex-column">
            <label class="form-label">Dokumen Terlampir</label>
            @if ($ad->documents)
              <a href="{{ asset('uploads/documents/ads/' . basename($ad->documents)) }}" class="btn btn-primary" disabled target="_blank">
                Lihat Dokumen
              </a>
            @else
              <input type="text" class="form-control" value="Tidak ada dokumen" disabled>
            @endif
          </div>
          
          <hr>

          <h5 class="mb-3">Status Pengiklanan</h5>
          <div class="mb-3 d-flex flex-column">
            @php
              $status = $ad->submission_status;
              $badgeClass = match($status) {
                  'disetujui' => 'bg-success',
                  'ditolak' => 'bg-danger',
                  default => 'bg-info',
              };
            @endphp
            <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
          </div>

          <div class="d-flex justify-content-between">
            <a href="{{ route('pengiklanan.staffIndex') }}" class="btn btn-secondary">Kembali</a>
            <div class="d-flex gap-3">
              @if ($ad->submission_status === 'diajukan')
                <div class="">
                  <form action="{{ route('pengiklanan.reject', $ad->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                  </form>
                </div>
                <div class="">
                  <form action="{{ route('pengiklanan.approve', $ad->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">Setujui Pengajuan</button>
                  </form>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
