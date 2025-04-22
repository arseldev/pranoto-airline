@extends('layouts.master')

@section('title')
  Role Management
@endsection

@section('css')
  <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1')
      Role
    @endslot
    @slot('li_2')
      {{ route('roles.index') }}
    @endslot
    @slot('title')
      Role List
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
        <div class="card-header d-flex justify-content-between">
          <h4 class="card-title">Daftar Role</h4>
          <a href='{{ route("roles.create") }}' class="btn btn-success btn-sm">+ Tambah Role</a>
        </div>
        <div class="card-body">
          <table id="roles-table" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama Role</th>
                <th>Permissions</th>
                <th>Dibuat</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>

  <script>
    $(function () {
      $('#roles-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("roles.index") }}',
        columns: [
          { data: 'id', name: 'id' },
          { data: 'name', name: 'name' },
          { data: 'permissions', name: 'permissions', orderable: false, searchable: false },
          { data: 'created_at', name: 'created_at' },
          { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
      });
    });

  </script>
@endsection
