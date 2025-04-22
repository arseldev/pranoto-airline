@extends('layouts.master')

@section('title')
  Manajemen Slider
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Slider @endslot
    @slot('title') Manajemen Slider @endslot
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

          <h4 class="card-title mb-4">Formulir Tambah Slider</h4>

          <form method="POST" action="{{ route('slider.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf

            <div class="mb-3">
              <label for="slider_name" class="form-label">Nama slider</label>
              <input type="text" class="form-control" id="slider_name" name="slider_name" value="{{ old('slider_name') }}" required>
              @error('slider_name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="documents" class="form-label">Foto yang akan ditampilkan</label>
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
