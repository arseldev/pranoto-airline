@extends('layouts.master')

@section('title')
  Laporan Keuangan
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1') Keuangan @endslot
    @slot('title') Tambah Laporan Keuangan @endslot
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
          <h4 class="card-title mb-4">Formulir Tambah Laporan Keuangan</h4>

          @if ($errors->has('finance'))
            <div class="alert alert-danger">
              {{ $errors->first('finance') }}
            </div>
          @endif

          <form action="{{ route('keuangan.store') }}" method="POST">
            @csrf
            <table class="table" id="financeTable">
              <thead>
                <tr>
                  <th>Aliran Dana</th>
                  <th>Jumlah</th>
                  <th>Periode</th>
                  <th>Catatan</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach(old('finance', []) as $index => $finance)
                  <tr>
                    <td>
                      <select name="finance[{{ $index }}][flow_type]" class="form-control flow_type">
                        <option value="">Pilih Aliran Dana</option>
                        <option value="in" {{ old("finance.$index.flow_type", $finance['flow_type'] ?? '') == 'in' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="out" {{ old("finance.$index.flow_type", $finance['flow_type'] ?? '') == 'out' ? 'selected' : '' }}>Pengeluaran</option>
                      </select>
                      @error("finance.$index.flow_type")
                        <div class="text-danger">{{ $message }}</div>
                      @enderror
                    </td>
                    <td>
                      <input type="number" name="finance[{{ $index }}][amount]" class="form-control" value="{{ old("finance.$index.amount", $finance['amount'] ?? '') }}" step="0.01">
                      @error("finance.$index.amount")
                        <div class="text-danger">{{ $message }}</div>
                      @enderror
                    </td>
                    <td>
                      <input type="month" name="finance[{{ $index }}][date]" class="form-control" value="{{ old("finance.$index.date", $finance['date'] ?? '') }}">
                      @error("finance.$index.date")
                        <div class="text-danger">{{ $message }}</div>
                      @enderror
                    </td>
                    <td>
                      <select name="finance[{{ $index }}][note]" class="form-control note">
                        <option value="">Pilih Catatan</option>
                        <!-- Menampilkan catatan berdasarkan aliran dana -->
                        @foreach($uniqueNotes['in'] as $note)
                          <option value="{{ $note }}" {{ old("finance.$index.note") == $note ? 'selected' : '' }}>
                            {{ $note }}
                          </option>
                        @endforeach
                      </select>
                      <!-- Input manual catatan baru -->
                      <input type="text" name="finance[{{ $index }}][note_manual]" class="form-control mt-2" placeholder="Tambahkan catatan baru">
                      @error("finance.$index.note")
                        <div class="text-danger">{{ $message }}</div>
                      @enderror
                    </td>
                    <td>
                      <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <div class="d-flex justify-content-end gap-3">
              <button type="button" class="btn btn-primary" id="addRow">Tambah Baris</button>
              <button type="submit" class="btn btn-success">Simpan</button>
            </div>
            @if ($errors->any())
              <div class="alert alert-danger mt-3">
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    let rowIndex = document.querySelectorAll('#financeTable tbody tr').length;

    // Fungsi untuk memuat catatan berdasarkan aliran dana yang dipilih
    function updateNotesDropdown() {
      const flowType = this.value;
      const noteSelect = this.closest('tr').querySelector('.note');
      noteSelect.innerHTML = '<option value="">Pilih Catatan</option>';
      const notes = @json($uniqueNotes);
      
      // Menambahkan catatan berdasarkan flow_type yang dipilih
      notes[flowType].forEach(function(note) {
        const option = document.createElement('option');
        option.value = note;
        option.textContent = note;
        noteSelect.appendChild(option);
      });
    }

    // Menambahkan baris baru
    document.getElementById('addRow').addEventListener('click', function () {
      const tableBody = document.querySelector('#financeTable tbody');
      const newRow = document.createElement('tr');

      newRow.innerHTML = `
        <td>
          <select name="finance[${rowIndex}][flow_type]" class="form-control flow_type">
            <option value="">Pilih Aliran Dana</option>
            <option value="in">Pemasukan</option>
            <option value="out">Pengeluaran</option>
          </select>
        </td>
        <td><input type="number" name="finance[${rowIndex}][amount]" class="form-control" step="0.01"></td>
        <td><input type="month" name="finance[${rowIndex}][date]" class="form-control"></td>
        <td>
          <select name="finance[${rowIndex}][note]" class="form-control note">
            <option value="">Pilih Catatan</option>
          </select>
          <input type="text" name="finance[${rowIndex}][note_manual]" class="form-control mt-2" placeholder="Tambahkan catatan baru">
        </td>
        <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
      `;

      tableBody.appendChild(newRow);
      rowIndex++;

      // Tambahkan event listener untuk setiap select flow_type baru
      newRow.querySelector('.flow_type').addEventListener('change', updateNotesDropdown);
    });

    // Hapus baris
    document.addEventListener('click', function (e) {
      if (e.target && e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
      }
    });

    // Menambahkan event listener untuk update dropdown notes berdasarkan flow_type yang dipilih
    document.querySelectorAll('.flow_type').forEach(function(select) {
      select.addEventListener('change', updateNotesDropdown);
    });
  </script>
@endpush
