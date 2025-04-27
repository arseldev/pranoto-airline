@extends('layouts.laravel-default')

@section('title', 'Laporan Keuangan | APT PRANOTO')

@section('content')
<section class="container py-5">
  <h2 class="mb-4">Laporan Keuangan Bandara A.P.T. Pranoto</h2>

  <section class="py-5">
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
          <div class="card w-50">
              <div class="card-body">
                <canvas id="pieKeuangan"></canvas>
              </div>
          </div>
        </div>
  </section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
@endpush
</section>
@endsection
