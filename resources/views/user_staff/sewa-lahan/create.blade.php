@extends('layouts.master')

@section('title')
  Pengajuan Sewa Lahan
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Sewa Lahan @endslot
    @slot('title') Pengajuan Sewa Lahan @endslot
  @endcomponent
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
  @endif
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body">

          <h4 class="card-title mb-4">Formulir Pengajuan Sewa Lahan</h4>

          <form method="POST" action="{{ route('sewa.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf

            <div class="mb-3">
              <label for="rental_name" class="form-label">Nama Sewa</label>
              <input type="text" class="form-control" id="rental_name" name="rental_name" value="{{ old('rental_name') }}" required>
              @error('rental_name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="rental_type" class="form-label">Jenis Sewa</label>
              <input type="text" class="form-control" id="rental_type" name="rental_type" value="{{ old('rental_type') }}" required>
              @error('rental_type')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="description" class="form-label">Deskripsi Sewa</label>
              <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
              @error('description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="documents" class="form-label">Dokumen yang Diperlukan</label>
              <input type="file" class="form-control" id="documents" name="documents" required>
              @error('documents')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary waves-effect waves-light">Ajukan Sekarang</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script>
    // Bootstrap form validation
    document.querySelector('form').addEventListener('submit', function (e) {
      if (!this.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      this.classList.add('was-validated');
    });
  </script>
@endsection
