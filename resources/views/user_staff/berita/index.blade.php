@extends('layouts.master')

@section('title')
  Berita
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Berita @endslot
    @slot('title') Berita @endslot
  @endcomponent
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
  @endif
  <div class="row">
    <div class="col-xl-12">
      

      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header d-flex justify-content-between bg-transparent">
                  <h4 class="card-title">Daftar Berita</h4>
                    <a href='{{ route("berita.create") }}' class="btn btn-success btn-sm">+ Tambah Berita</a>
                </div>
                <div class="card-body">
                  <table id="submission-table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Judul Berita</th>
                        <th>Headline</th>
                        <th>Publikasi</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>

                    @staff
                      <tbody>
                        @forelse ($news as $index => $new)
                          <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $new->title }}</td>
                            <td>
                              <form action="{{ route('berita.toggleHeadline', $new->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="is_headline" value="0">
                                <div class="form-check form-switch">
                                  <input 
                                    type="checkbox" 
                                    value="1"
                                    class="form-check-input" 
                                    name="is_headline" 
                                    {{ $new -> is_headline ? 'checked' : '' }} 
                                    onchange="this.form.submit()"
                                  >
                                </div>
                              </form>
                            </td>
                            <td>
                              <form action="{{ route('berita.togglePublish', $new->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="is_published" value="0">
                                <div class="form-check form-switch">
                                  <input 
                                    type="checkbox" 
                                    value="1"
                                    class="form-check-input" 
                                    name="is_published" 
                                    {{ $new -> is_published ? 'checked' : '' }} 
                                    onchange="this.form.submit()"
                                  >
                                </div>
                              </form>
                            </td>
                            <td class="w-25">{{ $new->created_at->format('d M Y H:i') }}</td>
                            <td>
                              <a href="{{ route('berita.show', $new->slug) }}" class="btn btn-primary btn-sm w-100">Lihat Berita</a>
                            </td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="4" class="text-center">Belum ada berita</td>
                          </tr>
                        @endforelse
                      </tbody>
                    @endstaff
                  </table>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script>

  </script>
@endsection
