@extends('layouts.master')

@section('title')
  Laporan Keuangan
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Keuangan @endslot
    @slot('title') Laporan Keuangan @endslot
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
                  <h4 class="card-title">Daftar Laporan Keuangan</h4>
                </div>

                <div class="card-header d-flex justify-content-between bg-transparent">
                  <form action="{{ route('keuangan.index') }}" method="GET">
                    <div class="d-flex gap-3">

                        <!-- Filter Tahun -->
                        <div>
                            <label for="year" class="form-label">Filter Tahun</label>
                            <select name="year" id="year" class="form-control pe-5">
                                <option value="">Semua Tahun</option>
                                @foreach($years as $year)
                                  <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Jenis Aliran Dana -->
                        <div>
                            <label for="filter" class="form-label">Filter Arus Dana</label>
                            <select name="filter" id="filter" class="form-control pe-5">
                                <option value="">Semua</option>
                                <option value="in" {{ request('filter') == 'in' ? 'selected' : '' }}>Pemasukan</option>
                                <option value="budget" {{ request('filter') == 'budget' ? 'selected' : '' }}>Anggaran</option>
                            </select>
                        </div>

                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                  </form>

                  <div class="d-flex align-items-end">
                    <a href="{{ route('keuangan.create') }}" class="btn btn-success btn-sm">+ Tambah Laporan Keuangan</a>
                  </div>
                </div>

                <div class="card-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Catatan</th>
                        <th>Dibuat pada</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($finances as $index => $finance)
                        <tr>
                          <td>{{ $index + 1 }}</td>
                          <td>{{ \Carbon\Carbon::parse($finance->date)->format('d M Y') }}</td>
                          <td>
                            @if($finance->flow_type === 'in')
                              <span class="badge bg-success">Pemasukan</span>
                            @else
                              <span class="badge bg-warning">Anggaran</span>
                            @endif
                          </td>
                          <td>Rp {{ number_format($finance->amount, 0, ',', '.') }}</td>
                          <td>{{ $finance->note ?? '-' }}</td>
                          <td>{{ $finance->created_at->format('d M Y') }}</td>
                          <td>
                            @if($finance->flow_type == 'budget')
                              <!-- Tombol dropdown untuk menampilkan pengeluaran terkait anggaran -->
                              <button class="btn btn-info btn-sm" data-bs-toggle="collapse" data-bs-target="#expenses-{{ $finance->id }}" aria-expanded="false" aria-controls="expenses-{{ $finance->id }}">
                                Lihat Pengeluaran
                              </button>
                              <div class="collapse" id="expenses-{{ $finance->id }}">
                                <table class="table table-bordered mt-3">
                                  <thead>
                                    <tr>
                                      <th>No</th>
                                      <th>Deskripsi</th>
                                      <th>Jumlah</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach($finance->expenses as $expense)
                                      <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $expense->description }}</td>
                                        <td>Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                      </tr>
                                    @endforeach
                                    @if($finance->expenses->isEmpty())
                                      <tr>
                                        <td colspan="3" class="text-center">Tidak ada pengeluaran untuk anggaran ini.</td>
                                      </tr>
                                    @endif
                                  </tbody>
                                </table>
                              </div>
                            @endif
                          </td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="7" class="text-center">Data tidak tersedia.</td>
                        </tr>
                      @endforelse
                    </tbody>
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
