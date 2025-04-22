@extends('layouts.master')

@section('title')
  Detail Pengajuan Tenant
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Tenant @endslot
    @slot('title') Detail Pengajuan Tenant @endslot
  @endcomponent

  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-4">Informasi Pengaju</h4>

          <div class="mb-3">
            <label class="form-label">Nama Pengaju</label>
            <input type="text" class="form-control" value="{{ $tenant->users->first()?->name ?? '-' }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Email Pengaju</label>
            <input type="text" class="form-control" value="{{ $tenant->users->first()?->email ?? '-' }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal Pengajuan</label>
            <input type="text" class="form-control" value="{{ $tenant->created_at->format('d M Y - H:i') }}" disabled>
          </div>

          <hr>

          <h5 class="mb-3">Detail Tenant</h5>
            <div class="mb-3">
              <label for="business_name" class="form-label">Nama Usaha</label>
              <input type="text" class="form-control" id="business_name" name="business_name" value="{{ $tenant->business_name }}" disabled>
            </div>

            <div class="mb-3">
              <label for="business_type" class="form-label">Jenis Usaha</label>
              <input type="text" class="form-control" id="business_type" name="business_type" value="{{ $tenant->business_type }}" disabled>
            </div>

            <div class="mb-3">
              <label for="description" class="form-label">Deskripsi Usaha</label>
              <textarea class="form-control" id="description" name="description" rows="4" disabled>{{ $tenant->description }}</textarea>
            </div>

            <div class="mb-3">
              <label for="rental_type" class="form-label">Jenis Sewa</label>
              <select class="form-select" id="rental_type" name="rental_type" disabled>
                @php
                  $options = [
                    'Ruangan di dalam terminal terbuka tanpa AC',
                    'Ruangan di dalam terminal tertutup tanpa AC',
                    'Ruangan di dalam terminal terbuka dengan AC',
                    'Ruangan di dalam terminal tertutup dengan AC',
                    'Ruangan di luar terminal terbuka tanpa AC',
                    'Ruangan di luar terminal tertutup tanpa AC',
                    'Ruangan di luar terminal terbuka dengan AC',
                    'ATM',
                    'Tanah',
                    'Tiang pancang reklame',
                  ];
                @endphp
                @foreach ($options as $option)
                  <option value="{{ $option }}" {{ $tenant->rental_type === $option ? 'selected' : '' }}>
                    {{ $option }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="mb-3 d-flex flex-column">
              <label class="form-label">Dokumen Terlampir</label>
              @if ($tenant->documents)
                <a href="{{ asset('uploads/documents/tenant/' . basename($tenant->documents)) }}" class="btn btn-primary" disabled target="_blank">
                  Lihat Dokumen
                </a>
              @else
                <input type="text" class="form-control" value="Tidak ada dokumen" disabled>
              @endif
            </div>

          <hr>

          <h5 class="mb-3">Status Sewa</h5>
          <div class="mb-3 d-flex flex-column">
            @php
              $status = $tenant->submission_status;
              $badgeClass = match($status) {
                  'disetujui' => 'bg-success',
                  'ditolak' => 'bg-danger',
                  default => 'bg-info',
              };
            @endphp
            <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
          </div>
          
          

          <div class="d-flex justify-content-between">
            <a href="{{ route('tenant.staffIndex') }}" class="btn btn-secondary">Kembali</a>
            <div class="d-flex gap-3">
              @if ($tenant->submission_status === 'diajukan')
                <div class="">
                  <form action="{{ route('tenant.approve', $tenant->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">Setujui Pengajuan</button>
                  </form>
                </div>
                <div class="">
                  <form action="{{ route('tenant.reject', $tenant->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
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
