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
                  <h4 class="card-title">Daftar Slider</h4>
                    <a href='{{ route("slider.create") }}' class="btn btn-success btn-sm">+ Tambah Slider</a>
                </div>
                <div class="card-body">
                  <table id="submission-table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nama File Slider</th>
                        <th>Gambar</th>
                        <th>Tampilkan di Beranda</th>
                        <th>Tampilkan di Footer</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>

                    @staff
                      <tbody>
                        @forelse ($sliders as $index => $slider)
                          <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $slider->documents ? preg_replace('/^\d+_/', '', basename($slider->documents)) : '-' }}</td>
                            <td>
                              @if ($slider->documents)
                                <img 
                                  src="{{ asset('uploads/' . $slider->documents) }}" 
                                  class="img-fluid w-75 rounded" 
                                  alt="Preview Gambar">
                              @else
                                <span class="text-muted">Tidak ada gambar</span>
                              @endif
                            </td>
                            <td>
                              <form action="{{ route('slider.toggleVisibilityHome', $slider->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="is_visible_home" value="0">
                                <div class="form-check form-switch">
                                  <input 
                                    type="checkbox" 
                                    value="1"
                                    class="form-check-input" 
                                    name="is_visible_home" 
                                    {{ $slider->is_visible_home ? 'checked' : '' }} 
                                    onchange="this.form.submit()"
                                  >
                                </div>
                              </form>
                            </td>
                            <td>
                              <form action="{{ route('slider.toggleVisibilityFooter', $slider->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="is_visible_footer" value="0">
                                <div class="form-check form-switch">
                                  <input 
                                    type="checkbox" 
                                    value="1"
                                    class="form-check-input" 
                                    name="is_visible_footer" 
                                    {{ $slider->is_visible_footer ? 'checked' : '' }} 
                                    onchange="this.form.submit()"
                                  >
                                </div>
                              </form>
                            </td>

                            <td class="w-25">{{ $slider->created_at->format('d M Y H:i') }}</td>
                            <td>
                              <form class="col" action="{{ route('slider.destroy', $slider->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100">Hapus gambar</button>
                              </form>
                            </td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="4" class="text-center">Belum ada slider</td>
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
