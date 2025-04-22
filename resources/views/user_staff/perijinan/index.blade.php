@extends('layouts.master')

@section('title')
  Pengajuan Perizinan Usaha
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Perizinan Usaha @endslot
    @slot('title') Pengajuan Perizinan Usaha @endslot
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

          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header d-flex justify-content-between bg-transparent">
                  <h4 class="card-title">Daftar Perizinan Usaha</h4>
                  @notadmin
                    @notstaff
                      <a href='{{ route("perijinan.create") }}' class="btn btn-success btn-sm">+ Tambah Pengajuan</a>
                    @endnotstaff
                  @endnotadmin
                </div>
                <div class="card-body">
                  <table id="submission-table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Nama File Pengajuan</th>
                        <th>Dibuat</th>
                        <th>Status</th>
                        @staff
                        <th>Pemilik</th>
                        @endstaff
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    @notadmin
                      @notstaff
                        <tbody>
                          @forelse ($licenses as $index => $license)
                            <tr>
                              <td>{{ $index + 1 }}</td>
                              <td>{{ $license->documents ? preg_replace('/^\d+_/', '', basename($license->documents)) : '-' }}</td>
                              <td>{{ $license->created_at->format('d M Y H:i') }}</td>
                              <td>
                                @php
                                  $status = $license->submission_status;
                                  $badgeClass = match($status) {
                                      'disetujui' => 'bg-success',
                                      'ditolak' => 'bg-danger',
                                      default => 'bg-info',
                                  };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                              </td>                          
                              <td>
                                @if ($license->documents)
                                  <div class="row">
                                    <form class="col">
                                      <a href="{{ asset('uploads/documents/license/' . basename($license->documents)) }}" class="btn btn-sm btn-primary w-100">Lihat Berkas</a>
                                    </form>
                                    @if ($license->submission_status == 'diajukan')
                                      <form class="col" action="{{ route('perijinan.destroy', $license->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100">Hapus pengajuan</button>
                                      </form>
                                    @endif
                                  </div>
                                @else
                                  <span class="text-muted">Tidak ada berkas</span>
                                @endif
                              </td>
                            </tr>
                          @empty
                            <tr>
                              <td colspan="4" class="text-center">Belum ada pengajuan perizinan usaha</td>
                            </tr>
                          @endforelse
                        </tbody>
                      @endnotstaff
                    @endnotadmin

                    @staff
                      <tbody>
                        @forelse ($licenses as $index => $license)
                          <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $license->documents ? preg_replace('/^\d+_/', '', basename($license->documents)) : '-' }}</td>
                            <td>{{ $license->created_at->format('d M Y H:i') }}</td>
                            <td>
                              @php
                                $status = $license->submission_status;
                                $badgeClass = match($status) {
                                    'disetujui' => 'bg-success',
                                    'ditolak' => 'bg-danger',
                                    default => 'bg-info',
                                };
                              @endphp
                              <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                            </td>
                            <td>
                              @foreach ($license->users as $user)
                                <span class="badge bg-secondary">{{ $user->name }}</span>
                              @endforeach
                            </td>
                            <td>
                              <div class="row g-1">
                                <div class="col-12 mb-1">
                                  <a href="{{  route('perijinan.show', $license->id) }}" class="btn btn-sm btn-primary w-100">
                                    Lihat
                                  </a>
                                </div>
                              </div>
                            </td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="4" class="text-center">Belum ada pengajuan perizinan usaha</td>
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
