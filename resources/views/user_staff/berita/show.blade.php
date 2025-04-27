@extends('layouts.master')

@section('title')
  Edit Berita
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Berita @endslot
    @slot('title') Edit Berita @endslot
  @endcomponent

  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-4">Edit Berita</h4>

          <form action="{{ route('berita.update', $news->slug) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="mb-3 d-flex flex-column">
              <label class="form-label">Gambar Berita</label>
              @if ($news->image)
                <img src="{{ asset('uploads/' . $news->image) }}" class="img-fluid w-50 mb-3">
              @else
                <p class="text-muted">Tidak ada gambar</p>
              @endif
              <input type="file" class="form-control" name="image">
              <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
            </div>

            <div class="mb-3">
              <label class="form-label">Judul Berita</label>
              <input type="text" name="title" class="form-control" value="{{ old('title', $news?->title) }}" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Isi Berita</label>
              <textarea name="content" class="form-control" rows="20" required>{{ old('content', $news?->content) }}</textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Tanggal Pembuatan</label>
              <input type="text" class="form-control" value="{{ $news->created_at->format('d M Y - H:i') }} WIB" disabled>
            </div>

            <div class="d-flex justify-content-between">
              <a href="{{ route('berita.staffIndex') }}" class="btn btn-secondary">Kembali</a>
              <div class="d-flex gap-2">
                <form 
                  action="{{ route('berita.destroy', $news->slug) }}" 
                  method="POST" 
                  onsubmit="return confirm('Yakin ingin menghapus berita ini?')"
                >
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger">Hapus Berita</button>
                </form>

                <button type="submit" class="btn btn-primary">Update Berita</button>
              </div>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
@endsection
