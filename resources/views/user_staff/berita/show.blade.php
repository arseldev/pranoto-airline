@extends('layouts.master')

@section('title')
  Detail Berita
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Berita @endslot
    @slot('title') Detail Berita @endslot
  @endcomponent

  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-4">Detail Berita</h4>

          <div class="mb-3 d-flex flex-column">
            <label class="form-label">Gambar Berita</label>
            @if ($news->image)
              <img src="{{ asset('uploads/' . $news->image) }}" class="img-fluid w-50">
            @else
              <input type="text" class="form-control" value="Tidak ada gambar" disabled>
            @endif
          </div>

          <div class="mb-3">
            <label class="form-label">Judul Berita</label>
            <input type="text" class="form-control" value="{{ $news?->title ?? '-' }}" disabled>
          </div>

          <div class="mb-3">
            <label class="form-label">Isi Berita</label>
            <textarea type="text" class="form-control" id="" rows="20" disabled>{{ $news?->content ?? '-' }}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal Pembuatan</label>
            <input type="text" class="form-control" value="{{ $news->created_at->format('d M Y - H:i') }} WIB" disabled>
          </div>

          <div class="d-flex justify-content-between">
            <a href="{{ route('berita.staffIndex') }}" class="btn btn-secondary">Kembali</a>
            <form 
              class="col d-flex justify-content-end" 
              action="{{ route('berita.destroy', $news->id) }}" 
              method="POST" 
              onsubmit="return confirm('Yakin ingin menghapus berita ini?')"
            >
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger">Hapus Berita</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
