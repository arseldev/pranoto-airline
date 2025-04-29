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
          @if ($errors->has('budget_expenses'))
              <div class="alert alert-danger">
                  {{ $errors->first('budget_expenses') }}
              </div>
          @endif


          <form method="POST" action="{{ route('keuangan.store') }}">
            @csrf

            <!-- Flow Type -->
            <div class="form-group">
              <label for="flow_type">Aliran Dana</label>
              <select name="finance[0][flow_type]" id="flow_type" class="form-control" onchange="toggleBudgetFields()">
                <option value="">-- Pilih --</option>
                <option value="in">Pemasukan</option>
                <option value="budget">Anggaran</option>
              </select>
            </div>

            <!-- Amount -->
            <div class="form-group">
              <label for="amount">Jumlah</label>
              <input type="number" name="finance[0][amount]" class="form-control" min="1" required>
            </div>

            <!-- Date -->
            <div class="form-group">
              <label for="date">Periode (YYYY-MM)</label>
              <input type="month" name="finance[0][date]" class="form-control" required>
            </div>

            <!-- Note -->
            <div class="form-group">
              <label for="note">Catatan</label>
              <textarea name="finance[0][note]" class="form-control"></textarea>
            </div>

            <!-- Budget Expenses Section -->
            <div id="budget-expenses-section" style="display:none; margin-top: 20px;">
              <h5>Detail Pengeluaran</h5>
              <table class="table" id="budget-expenses-table">
                  <thead>
                      <tr>
                          <th>Deskripsi</th>
                          <th>Jumlah</th>
                          <th>Aksi</th>
                      </tr>
                  </thead>
                  <tbody>
                      <!-- Dynamic rows will be added here -->
                  </tbody>
              </table>
              <div id="total-expense-wrapper" style="display:none; margin-top: 10px;">
                <strong>Total Pengeluaran: Rp <span id="total-expense">0</span></strong>
            </div>
              <button type="button" class="btn btn-sm btn-primary" onclick="addExpenseRow()">+ Tambah Baris</button>
            </div>

            <button type="submit" class="btn btn-success mt-3">Simpan</button>
        </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
      // Fungsi untuk menghitung total pengeluaran
      function calculateTotalExpense() {
          let totalExpense = 0;
          // Ambil semua elemen input jumlah pengeluaran
          document.querySelectorAll('.expense-amount').forEach(function (input) {
              totalExpense += parseInt(input.value) || 0; // Menambahkan jumlah pengeluaran
          });
          return totalExpense;
      }

      // Fungsi validasi form sebelum submit
      function validateForm(event) {
          const budgetAmount = parseInt(document.querySelector('#budget-amount').value); // Anggaran yang dimasukkan
          const totalExpense = calculateTotalExpense(); // Total pengeluaran

          if (totalExpense > budgetAmount) {
              // Menampilkan pesan error jika pengeluaran melebihi anggaran
              document.querySelector('#expense-error').textContent = 'Total pengeluaran tidak boleh melebihi jumlah anggaran.';
              event.preventDefault(); // Mencegah form disubmit
          } else {
              // Kosongkan pesan error jika valid
              document.querySelector('#expense-error').textContent = '';
          }
      }

      // Event listener saat form disubmit
      document.querySelector('#finance-form').addEventListener('submit', validateForm);
  </script>
  <script>
    function toggleBudgetFields() {
        const flowType = document.getElementById('flow_type').value;
        const budgetSection = document.getElementById('budget-expenses-section');
        const totalExpenseWrapper = document.getElementById('total-expense-wrapper');
        if (flowType === 'budget') {
            budgetSection.style.display = 'block';
            totalExpenseWrapper.style.display = 'block';
        } else {
            budgetSection.style.display = 'none';
            totalExpenseWrapper.style.display = 'none';
        }
        calculateTotalExpense();
    }

    function addExpenseRow() {
        const tableBody = document.getElementById('budget-expenses-table').querySelector('tbody');
        const rowIndex = tableBody.rows.length;
        const row = `
            <tr>
                <td><input type="text" name="budget_expenses[${rowIndex}][description]" class="form-control" required></td>
                <td>
                    <input type="number" name="budget_expenses[${rowIndex}][amount]" class="form-control expense-amount" min="1" required oninput="calculateTotalExpense()">
                </td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeExpenseRow(this)">Hapus</button></td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', row);
        calculateTotalExpense();
    }

    function removeExpenseRow(button) {
        button.closest('tr').remove();
        calculateTotalExpense();
    }

    function calculateTotalExpense() {
        let total = 0;
        document.querySelectorAll('.expense-amount').forEach(function(input) {
            const val = parseInt(input.value) || 0;
            total += val;
        });
        document.getElementById('total-expense').innerText = total.toLocaleString('id-ID');
    }
  </script>
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
