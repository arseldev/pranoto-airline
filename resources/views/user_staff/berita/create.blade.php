@extends('layouts.master')

@section('title')
Berita
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Berita @endslot
    @slot('title')Berita @endslot
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

          <h4 class="card-title mb-4">Tambah Berita</h4>

          <form method="POST" action="{{ route('berita.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf

            <div class="mb-3">
              <label for="image" class="form-label">Gambar yang akan ditampilkan</label>
              <input type="file" class="form-control" id="image" name="image" required>
              @error('image')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="title" class="form-label">Judul berita</label>
              <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
              @error('title')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="content" class="form-label">Isi berita</label>
              <textarea class="form-control" id="content" name="content" rows="20" required>{{ old('content') }}</textarea>
              @error('content')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary waves-effect waves-light">Tambah Berita</button>
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
