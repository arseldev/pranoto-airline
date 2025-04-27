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
    <h2 class="text-center mb-2 fs-4">Grafik Pemasukan APT Pranoto</h2>
    <div class="d-flex justify-content-center">
      <div class="card mb-5 w-75">
        <div class="card-body">
            <canvas id="grafikKeuangan"></canvas>
        </div>
      </div>
    </div>
    <h2 class="text-center mb-2 fs-4">Grafik Pai Arus Kas APT Pranoto</h2>
    <div class="d-flex justify-content-center">
      <div class="card w-75">
          <div class="card-body">
            <canvas id="pieKeuangan"></canvas>
          </div>
      </div>
    </div>
  </section>
  <!-- end row -->
@endsection
@section('script')
  <!-- Chart JS -->
  <script src="{{ URL::asset('/assets/libs/chart-js/chart-js.min.js') }}"></script>
  <!-- Magnific Popup-->
  <script src="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.js') }}"></script>

  <script>
    const ctx = document.getElementById('grafikKeuangan').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: {!! json_encode($labels) !!}, // Data label (Bulan)
        datasets: [{
          label: 'Pemasukan (Rp)',
          data: {!! json_encode($dataPemasukan) !!}, // Data Pemasukan
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
        }
      }
    });

    const pieCtx = document.getElementById('pieKeuangan').getContext('2d');
    new Chart(pieCtx, {
      type: 'pie',
      data: {
        labels: ['Pemasukan', 'Pengeluaran'],
        datasets: [{
          data: [{{ $totalPemasukan }}, {{ $totalPengeluaran }}],
          backgroundColor: [
            'rgba(54, 162, 235, 0.7)', // biru (pemasukan)
            'rgba(255, 99, 132, 0.7)'  // merah (pengeluaran)
          ],
          borderColor: [
            'rgba(54, 162, 235, 1)',
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
