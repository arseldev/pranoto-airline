@extends('layouts.laravel-default')

@section('title', 'Laporan Keuangan | APT PRANOTO')

@push('styles')
    <style>
      .select-filter {
        width: 150px;
      }

    </style>
@endpush

@section('content')
<section class="container pb-5">
  <h2 class="mb-4 fw-bold fs-1">Laporan Keuangan Bandara A.P.T. Pranoto</h2>

  {{-- Filter Form untuk Grafik Batang --}}
  <div class="card mb-5">
    <div class="card-header">
      <h5 class="mb-0">Filter Grafik Pemasukan</h5>
    </div>
    <div class="card-body">
      <form method="GET" action="{{ route('laporanKeuangan') }}" id="formGrafikBatang">
        <div class="row g-3 align-items-center">
          {{-- Jenis Pertumbuhan --}}
          <div class="col-auto">
            <label for="jenis_filter" class="col-form-label">Pertumbuhan</label>
          </div>
          <div class="col-auto">
            <select name="jenis_filter" id="jenis_filter" class="form-select select-filter">
              <option value="bulan" {{ ($jenis_filter == 'bulan' || !$jenis_filter) ? 'selected' : '' }}>Per Bulan</option>
              <option value="tahun" {{ $jenis_filter == 'tahun' ? 'selected' : '' }}>Per Tahun</option>
            </select>
          </div>

          {{-- Pilih Tahun - Hanya tampilkan untuk filter bulan --}}
          <div class="col-auto" id="tahun-container" style="{{ $jenis_filter == 'tahun' ? 'display:none;' : '' }}">
            <div class="d-flex gap-2">
              <label for="tahunSelect" class="col-form-label">Tahun</label>
              <select name="tahun" id="tahunSelect" class="form-select select-filter">
                @foreach ($years as $year)
                  <option value="{{ $year }}" {{ $filterTahun == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
              </select>
            </div>
          </div>

          {{-- Menyimpan nilai filter tahun pie saat form ini disubmit --}}
          <input type="hidden" name="tahun_pie" value="{{ $filterTahunPie }}">
          @if(isset($anggaran))
          <input type="hidden" name="anggaran" value="{{ $anggaran }}">
          @endif

          <div class="col-auto">
            <button type="submit" class="btn btn-primary">Terapkan</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Grafik Batang --}}
  <div class="card mb-5">
    <div class="card-header">
      <h5 class="mb-0">
        @if($jenis_filter == 'bulan')
          Grafik Pemasukan APT Pranoto Tahun {{ $filterTahun }} (Per Bulan)
        @else
          Grafik Pemasukan APT Pranoto (Per Tahun)
        @endif
      </h5>
    </div>
    <div class="card-body">
      <canvas id="grafikKeuangan"></canvas>
    </div>
  </div>

  {{-- Filter Form untuk Grafik Pie --}}
  <div class="card mb-5">
    <div class="card-header">
      <h5 class="mb-0">Filter Grafik Anggaran vs Pengeluaran</h5>
    </div>
    <div class="card-body">
      <form method="GET" action="{{ route('laporanKeuangan') }}" id="formGrafikPie">
        <div class="row g-3 align-items-center">
          <div class="col-auto">
            <label for="tahunPieSelect" class="col-form-label">Pilih Tahun</label>
          </div>
          <div class="col-auto">
            <select name="tahun_pie" id="tahunPieSelect" class="form-select select-filter" >
              @foreach ($years as $year)
                <option value="{{ $year }}" {{ $filterTahunPie == $year ? 'selected' : '' }}>{{ $year }}</option>
              @endforeach
            </select>
          </div>

          {{-- Menyimpan nilai filter lainnya saat form ini disubmit --}}
          <input type="hidden" name="jenis_filter" value="{{ $jenis_filter }}">
          <input type="hidden" name="tahun" value="{{ $filterTahun }}">

          <div class="col-auto">
            <button type="submit" class="btn btn-primary">Tampilkan Grafik</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Grafik Pie - Hanya ditampilkan jika data anggaran sudah tersedia --}}
  @if(isset($showPieChart) && $showPieChart)
  <div class="card mb-5">
    <div class="card-header">
      <h5 class="mb-0">Grafik Pie Anggaran vs Pengeluaran APT Pranoto Tahun {{ $filterTahunPie }}</h5>
    </div>
    <div class="d-flex justify-content-center">
      <div class="w-50">
        <div class="card-body">
          <canvas id="pieKeuangan"></canvas>
        </div>
      </div>
    </div>
    <div class="card-footer text-muted">
      <div class="row">
        <div class="col-md-6">
          <p><strong>Anggaran:</strong> Rp {{ number_format($anggaran, 0, ',', '.') }}</p>
        </div>
        <div class="col-md-6">
          <p><strong>Total Pengeluaran:</strong> Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
        </div>
        <div class="col-12">
          @if($anggaran > $totalPengeluaran)
            <div class="alert alert-success">
              <strong>Sisa Anggaran:</strong> Rp {{ number_format($anggaran - $totalPengeluaran, 0, ',', '.') }} ({{ round(($anggaran - $totalPengeluaran) / $anggaran * 100, 2) }}% dari anggaran)
            </div>
          @else
            <div class="alert alert-danger">
              <strong>Kelebihan Pengeluaran:</strong> Rp {{ number_format($totalPengeluaran - $anggaran, 0, ',', '.') }} ({{ round(($totalPengeluaran - $anggaran) / $anggaran * 100, 2) }}% dari anggaran)
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  @endif
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const jenisFilter = document.getElementById('jenis_filter');
  const tahunContainer = document.getElementById('tahun-container');

  function updateFilterState() {
    if (jenisFilter.value === 'bulan') {
      tahunContainer.style.display = 'block'; // Menampilkan dropdown tahun
    } else {
      tahunContainer.style.display = 'none'; // Menyembunyikan dropdown tahun
    }
  }

  // Jalankan setiap kali pilihan jenis filter berubah
  jenisFilter.addEventListener('change', updateFilterState);
</script>

<script>
  const ctx = document.getElementById('grafikKeuangan').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: {!! json_encode($labels) !!},
      datasets: [{
        label: 'Pemasukan (Rp)',
        data: {!! json_encode($dataPemasukan) !!},
        backgroundColor: 'rgba(54, 162, 235, 0.7)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return 'Rp ' + value.toLocaleString('id-ID');
            }
          }
        }
      },
      plugins: {
        tooltip: {
          callbacks: {
            label: function(context) {
              return 'Pemasukan: Rp ' + context.raw.toLocaleString('id-ID');
            }
          }
        }
      }
    }
  });

  @if(isset($showPieChart) && $showPieChart)
  const pieCtx = document.getElementById('pieKeuangan').getContext('2d');
  new Chart(pieCtx, {
    type: 'pie',
    data: {
      labels: ['Anggaran', 'Pengeluaran'],
      datasets: [{
        data: [{{ $anggaran }}, {{ $totalPengeluaran }}],
        backgroundColor: [
          'rgba(75, 192, 192, 0.7)',
          'rgba(255, 99, 132, 0.7)'
        ],
        borderColor: [
          'rgba(75, 192, 192, 1)',
          'rgba(255, 99, 132, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom',
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              let label = context.label || '';
              let value = context.parsed || 0;
              return `${label}: Rp ${value.toLocaleString('id-ID')}`;
            }
          }
        }
      }
    }
  });
  @endif
</script>
@endpush
@endsection
