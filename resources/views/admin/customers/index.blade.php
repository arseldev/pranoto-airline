@extends('layouts.master')

@section('title')
  Customers
@endsection

@section('css')
  <!-- DataTables -->
  <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1')
      Customers
    @endslot
    @slot('li_2')
      {{ route('customers.index') }}
    @endslot
    @slot('title')
      Customers List
    @endslot
  @endcomponent
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
  @endif

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <table id="datatable" class="table-hover table-bordered nowrap w-100 table">
            <h5>Daftar Pengguna</h5>
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Tiket</th>
                <th>Dibuat Pada</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div> <!-- end col -->
  </div> <!-- end row -->
@endsection

@section('script')
  <!-- Required datatable js -->
  <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>

  {{-- datatable init --}}
  <script type="text/javascript">
    $(function() {
      let table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        lengthChange: true,
        lengthMenu: [10, 20, 50, 100],
        pageLength: 10,
        scrollX: true,
        order: [
          [0, "desc"]
        ],
        language: {
          search: "Cari nama:",
          lengthMenu: "Menampilkan _MENU_ data",
          processing: "Memuat...",
          info: "@lang('translation.infoShowing') _START_ @lang('translation.infoTo') _END_ @lang('translation.infoOf') _TOTAL_ @lang('translation.infoEntries')",
          emptyTable: "@lang('translation.emptyTable')",
          paginate: {
            "first": "@lang('translation.paginateFirst')",
            "last": "@lang('translation.paginateLast')",
            "next": "@lang('translation.paginateNext')",
            "previous": "@lang('translation.paginatePrevious')"
          },
        },
        ajax: {
            url: "{{ route('customers.index') }}",
            data: function(d) {}
        },
        columns: [
          { data: 'id' },
          { data: 'name' },
          { data: 'email' },
          { data: 'phone', orderable: false },
          { data: 'tickets_count', searchable: false },
          { data: 'created_at' },
          {
            data: 'is_accepted',
            render: function(data) {
              return data == 1 ? '<span class="badge bg-success">Terverikasi</span>' : '<span class="badge bg-danger">Belum Terverikasi</span>';
            }
          },
          { data: 'action', orderable: false, searchable: false }
        ],
      });

      new $.fn.dataTable.Buttons(table, {
        buttons: [{
          extend: 'colvis',
          text: "@lang('translation.colvisBtn')"
        }]
      });

      table.buttons().container().prependTo($('#action_btns'));
      $('.dataTables_length select').addClass('form-select form-select-sm');
      $('.dataTables_info, .dataTables_paginate').addClass('mt-3');
    });
  </script>
@endsection

