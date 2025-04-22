@extends('layouts.master')

@section('title')
  Pengajuan Fieldtrip
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Fieldtrip @endslot
    @slot('title') Pengajuan Fieldtrip @endslot
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

          <h4 class="card-title mb-4">Formulir Pengajuan Fieldtrip</h4>

          <form method="POST" action="{{ route('fieldtrip.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf

            <div class="mb-3">
              <label for="fieldtrip_name" class="form-label">Nama Fieldtrip</label>
              <input type="text" class="form-control" id="fieldtrip_name" name="fieldtrip_name" value="{{ old('fieldtrip_name') }}" required>
              @error('fieldtrip_name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="fieldtrip_type" class="form-label">Jenis Fieldtrip</label>
              <input type="text" class="form-control" id="fieldtrip_type" name="fieldtrip_type" value="{{ old('fieldtrip_type') }}" required>
              @error('fieldtrip_type')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="description" class="form-label">Deskripsi Fieldtrip</label>
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
