@extends('layouts.master')

@section('title')
  @lang('sidebar.dashboard')
@endsection

@section('css')
  <!-- Lightbox css -->
  <link href="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1')
      Dashboards
    @endslot
    @slot('title')
      Dashboard
    @endslot
  @endcomponent
  <section class="hubud-secondary">
  <h3 class="mb-4">Grafik Pengunjung Website (7 Hari Terakhir)</h3>

  <canvas id="visitorChart" width="400" height="200"></canvas>

  <h2 class="mb-4">Laporan Keuangan Bandara A.P.T. Pranoto</h2>

  {{-- Filter Form untuk Grafik Batang --}}
  <div class="card mb-5">
    <div class="card-header">
      <h5 class="mb-0">Filter Grafik Pemasukan</h5>
    </div>
    <div class="card-body">
      <form method="GET" action="{{ route('root') }}" id="formGrafikBatang">
        <div class="row g-3 align-items-center">
          {{-- Jenis Pertumbuhan --}}
          <div class="col-auto">
            <label for="jenis_filter" class="col-form-label">Pertumbuhan</label>
          </div>
          <div class="col-auto">
            <select name="jenis_filter" id="jenis_filter" class="form-select">
              <option value="bulan" {{ ($jenis_filter == 'bulan' || !$jenis_filter) ? 'selected' : '' }}>Per Bulan</option>
              <option value="tahun" {{ $jenis_filter == 'tahun' ? 'selected' : '' }}>Per Tahun</option>
            </select>
          </div>

          {{-- Pilih Tahun - Hanya tampilkan untuk filter bulan --}}
          <div class="col-auto" id="tahun-container" style="{{ $jenis_filter == 'tahun' ? 'display:none;' : '' }}">
            <div class="d-flex gap-2">
              <label for="tahunSelect" class="col-form-label">Tahun</label>
              <select name="tahun" id="tahunSelect" class="form-select">
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
      <canvas id="barPemasukanChart"></canvas>
    </div>
  </div>

  {{-- Filter Form untuk Grafik Pie --}}
  <div class="card mb-5">
    <div class="card-header">
      <h5 class="mb-0">Filter Grafik Anggaran vs Pengeluaran</h5>
    </div>
    <div class="card-body">
      <form method="GET" action="{{ route('root') }}" id="formGrafikPie">
        <div class="row g-3 align-items-center">
          <div class="col-auto">
            <label for="tahunPieSelect" class="col-form-label">Pilih Tahun</label>
          </div>
          <div class="col-auto">
            <select name="tahun_pie" id="tahunPieSelect" class="form-select">
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
      <div class="card-body">
        <canvas id="pieAnggaranChart"></canvas>
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
  <!-- end row -->
@endsection
@section('script')
  <!-- Chart JS -->
  <script src="{{ URL::asset('/assets/libs/chart-js/chart-js.min.js') }}"></script>
  <!-- Magnific Popup-->
  <script src="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.js') }}"></script>

  <script>
    const ctx = document.getElementById('visitorChart').getContext('2d');

    const visitorChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: {!! json_encode($dates) !!}, // tanggal-tanggal
        datasets: [{
          label: 'Jumlah Pengunjung',
          data: {!! json_encode($totals) !!}, // total pengunjung
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1,
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1 // biar enak kelihatan kalau cuma 1-5 pengunjung
            }
          }
        }
      }
    });
  </script>

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
  // Grafik Bar Pemasukan
  const barPemasukanCtx = document.getElementById('barPemasukanChart').getContext('2d');
  const barPemasukanChart = new Chart(barPemasukanCtx, {
      type: 'bar',
      data: {
          labels: {!! json_encode($labels) !!},
          datasets: [{
              label: 'Total Pemasukan',
              data: {!! json_encode($dataPemasukan) !!},
              backgroundColor: 'rgba(54, 162, 235, 0.6)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1
          }]
      },
      options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
              y: {
                  beginAtZero: true,
                  ticks: {
                      callback: function(value) {
                          return 'Rp ' + value.toLocaleString();
                      }
                  }
              }
          }
      }
  });

  // Grafik Pie Anggaran vs Pengeluaran
  @if ($showPieChart)
  const pieAnggaranCtx = document.getElementById('pieAnggaranChart').getContext('2d');
  const pieAnggaranChart = new Chart(pieAnggaranCtx, {
      type: 'pie',
      data: {
          labels: ['Anggaran', 'Pengeluaran'],
          datasets: [{
              data: [
                  {{ $anggaran }},
                  {{ $totalPengeluaran }}
              ],
              backgroundColor: [
                'rgba(75, 192, 192, 0.7)',
                'rgba(255, 99, 132, 0.7)'
              ],
              borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)'
              ],
              borderWidth: 2
          }]
      },
      options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
              legend: {
                  position: 'bottom'
              }
          }
      }
  });
  @endif
  </script>

  {{-- 
  @if ($data['expenseChart'])
    {!! $data['expenseChart']->renderJs() !!}
  @endif --}}

  <script>
    // light box init
    $(".productImageLightBox").magnificPopup({
      type: "image",
      closeOnContentClick: !0,
      closeBtnInside: !1,
      fixedContentPos: !0,
      mainClass: "mfp-no-margins mfp-with-zoom",
      image: {
        verticalFit: !0
      },
      zoom: {
        enabled: !0,
        duration: 300
      }
    });

    // order status chart
    let flightStatusChart = @json($data['flightStatusChart']);
    let flightStatusLabel = [];
    let flightStatusData = [];
    let flightStatusColor = [];

    flightStatusChart.forEach(item => {
      flightStatusLabel.push(item.label);
      flightStatusData.push(item.total);
      flightStatusColor.push(item.color);
    });

    ! function(l) {
      "use strict";

      function r() {}

      r.prototype.respChart = function(r, o, e, a) {
        Chart.defaults.global.defaultFontColor = "#8791af",
          Chart.defaults.scale.gridLines.color = "rgba(166, 176, 207, 0.1)";
        var t = r.get(0).getContext("2d"),
          n = l(r).parent();

        function i() {
          r.attr("width", l(n).width());

          switch (o) {
            case "Line":
              new Chart(t, {
                type: "line",
                data: e,
                options: a
              });
              break;

            case "Doughnut":
              new Chart(t, {
                type: "doughnut",
                data: e,
                options: a
              });
              break;

            case "Pie":
              new Chart(t, {
                type: "pie",
                data: e,
                options: a
              });
              break;

            case "Bar":
              new Chart(t, {
                type: "bar",
                data: e,
                options: a
              });
              break;
          }
        }

        l(window).resize(i), i();
      }, r.prototype.init = function() {
        // order payment chart
        this.respChart(l("#flightStatusChart"), "Doughnut", {
        //   labels: ["Take Off", "Landing", "Canceled"],
          labels: ["Take Off", "Landing"],
          datasets: [{
            data: flightStatusData,
            backgroundColor: flightStatusColor,
            hoverBackgroundColor: flightStatusColor,
            hoverBorderColor: "#fff"
          }]
        });
      }, l.ChartJs = new r(), l.ChartJs.Constructor = r;
    }(window.jQuery),
    function() {
      "use strict";

      window.jQuery.ChartJs.init();
    }();
  </script>
@endsection
