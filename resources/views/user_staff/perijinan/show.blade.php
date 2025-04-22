@extends('layouts.master')

@section('title')
  Detail Pengajuan Perizinan Usaha
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Perizinan Usaha @endslot
    @slot('title') Detail Pengajuan Perizinan Usaha @endslot
  @endcomponent

  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-4">Informasi Pengaju</h4>

          <div class="mb-3">
            <label class="form-label">Nama Pengaju</label>
            <input type="text" class="form-control" value="{{ $license->users->first()?->name ?? '-' }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Email Pengaju</label>
            <input type="text" class="form-control" value="{{ $license->users->first()?->email ?? '-' }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal Pengajuan</label>
            <input type="text" class="form-control" value="{{ $license->created_at->format('d M Y - H:i') }}" disabled>
          </div>

          <hr>

          <h5 class="mb-3">Detail Perizinan Usaha</h5>

          <div class="mb-3">
            <label for="license_name" class="form-label">Nama Perizinan Usaha</label>
            <input type="text" class="form-control" id="license_name" value="{{ $license->license_name }}" disabled>
          </div>

          <div class="mb-3">
            <label for="license_type" class="form-label">Jenis Perizinan Usaha</label>
            <input type="text" class="form-control" id="license_type" value="{{ $license->license_type }}" disabled>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Deskripsi Perizinan Usaha</label>
            <textarea class="form-control" id="description" rows="4" disabled>{{ $license->description }}</textarea>
          </div>

          <div class="mb-3 d-flex flex-column">
            <label class="form-label">Dokumen Terlampir</label>
            @if ($license->documents)
              <a href="{{ asset('uploads/documents/license/' . basename($license->documents)) }}" class="btn btn-primary" disabled target="_blank">
                Lihat Dokumen
              </a>
            @else
              <input type="text" class="form-control" value="Tidak ada dokumen" disabled>
            @endif
          </div>
          
          <hr>

          <h5 class="mb-3">Status Perizinan Usaha</h5>
          <div class="mb-3 d-flex flex-column">
            @php
              $status = $license->submission_status;
              $badgeClass = match($status) {
                  'disetujui' => 'bg-success',
                  'ditolak' => 'bg-danger',
                  default => 'bg-info',
              };
            @endphp
            <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
          </div>
          
          

          <div class="d-flex justify-content-between">
            <a href="{{ route('perijinan.staffIndex') }}" class="btn btn-secondary">Kembali</a>
            <div class="d-flex gap-3">
              @if ($license->submission_status === 'diajukan')
                <div class="">
                  <form action="{{ route('perijinan.reject', $license->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                  </form>
                </div>
                <div class="">
                  <form action="{{ route('perijinan.approve', $license->id) }}" method="POST">
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
