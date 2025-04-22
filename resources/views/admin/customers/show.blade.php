@extends('layouts.master')

@section('title')
  @lang('translation.resource_info', ['resource' => __('attributes.customer')])
@endsection

@section('css')
  <!-- Lightbox css -->
  <link href="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />
  <!-- DataTables -->
  <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1')
      Customer
    @endslot
    @slot('li_2')
      {{ route('customers.index') }}
    @endslot
    @slot('title')
      @lang('translation.resource_info', ['resource' => __('attributes.customer')])
    @endslot
  @endcomponent
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
  @endif

  <div class="row">
    <div class="">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-4">Informasi Pengguna</h4>

          <div class="mb-3">
            <h6 class="fw-semibold mb-1">Nama</h6>
            <p class="text-muted">{{ $user->name }}</p>
          </div>

          <div class="mb-3">
            <h6 class="fw-semibold mb-1">Email</h6>
            <p class="text-muted">{{ $user->email }}</p>
          </div>

          <div class="mb-3">
            <h6 class="fw-semibold mb-1">Telepon</h6>
            <p class="text-muted">{{ $user->phone }}</p>
          </div>

          <div class="mb-3">
            <h6 class="fw-semibold mb-1">Alamat</h6>
            <p class="text-muted">{{ $user->address }}</p>
          </div>

          <div class="mb-3">
            <h6 class="fw-semibold mb-1">Jumlah Tiket</h6>
            <span class="badge bg-info text-dark fs-6">
              {{ $user->tickets()->count() }}
            </span>
          </div>
          @if ($user->is_accepted)
            {{-- Jika user sudah terverifikasi --}}
            
            {{-- Tombol Batalkan Verifikasi --}}
            <form action="{{ route('customers.unverify', $user->id) }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-warning">Batalkan Verifikasi Akun</button>
            </form>

            {{-- Toggle Staff --}}
            @if ($user->is_staff)
              <form action="{{ route('customers.toggleStaff', $user->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger">Jadikan Sebagai Pengguna</button>
              </form>
            @else
              <form action="{{ route('customers.toggleStaff', $user->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary">Jadikan Sebagai Staff</button>
              </form>
            @endif

          @else
            {{-- Jika user belum terverifikasi --}}
            <form action="{{ route('customers.verify', $user->id) }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-success">Verifikasi User</button>
            </form>
          @endif
          
        </div>
      </div>
    </div>
  </div>

  {{-- role management --}}
  @if ($user->is_staff)
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
        </div>
        <div class="card-body">
        <h5>Role Staff</h5>
          <table id="roles-table" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama Role</th>
                <th>Permissions</th>
                <th>Dibuat</th>
                <th>Aktifkan Role</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($roles as $index => $role)
                <tr>
                  <td>{{ $role->id }}</td>
                  <td>{{ $role->name }}</td>
                  <td>
                  @foreach ($role->permissions as $permission)
                    @php
                        $color = $colorMap[$permission->permission_name] ?? 'secondary';
                    @endphp
                      <span class="badge bg-{{ $color }}">{{ $permission->permission_name }}</span>
                  @endforeach
                  </td>
                  <td>{{ $role->created_at->format('d M Y') }}</td>
                  <td>
                    <form action="{{ route('customers.toggle-role', $user->id) }}" method="POST">
                      @csrf
                      <input type="hidden" name="role_id" value="{{ $role->id }}">
                      <div class="form-check form-switch">
                          <input 
                              class="form-check-input" 
                              type="checkbox" 
                              onchange="this.form.submit()"
                              {{ in_array($role->id, $userRoles) ? 'checked' : '' }}
                          >
                      </div>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  @endif


  {{-- show  user tickets --}}
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h5>User Tickets</h5>
          <div class="d-flex justify-content-end mb-4" id="action_btns">
          </div>
          <table id="datatable" class="table-hover table-bordered nowrap w-100 table">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th> @lang('translation.flight.flight_number')</th>
                <th> @lang('translation.flight.origin')</th>
                <th> @lang('translation.flight.time')</th>
                <th> Status</th>
                <th> @lang('translation.actions')</th>
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
  <!-- Magnific Popup-->
  <script src="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.js') }}"></script>
  <!-- Required datatable js -->
  <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>

  <script>
    // magnific-popup
    $(".airlineImageLightBox").magnificPopup({
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
    })
  </script>

  {{-- datatable init --}}
  <script type="text/javascript">
    $(function() {
      table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        lengthChange: true,
        lengthMenu: [10, 20, 50, 100],
        pageLength: 10,
        scrollX: true,
        order: [
          [0, "desc"]
        ],
        // text transalations
        language: {
          search: "@lang('translation.search')",
          lengthMenu: "@lang('translation.lengthMenu1') _MENU_ @lang('translation.lengthMenu2')",
          processing: "@lang('translation.processing')",
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
          url: "{{ route('customers.show', $user->id) }}",
          method: "GET",
        },
        columnDefs: [{
          className: "text-center",
          targets: 5
        }],
        columns: [{
            data: 'id'
          },
          {
            data: 'flight_info'
          },
          {
            data: 'route'
          },
          {
            data: 'time'
          },
          {
            data: 'status'
          },
          {
            data: 'action',
            orderable: false,
            searchable: false
          },
        ],
      })

      // select dropdown for change the page length
      $('.dataTables_length select').addClass('form-select form-select-sm');

      // add margin top to the pagination and info 
      $('.dataTables_info, .dataTables_paginate').addClass('mt-3');
    });
  </script>
@endsection
