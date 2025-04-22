<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::with('permissions');
            return DataTables::of($data)->addIndexColumn()
                ->setRowClass(fn ($row) => 'align-middle')
                ->addColumn('permissions', function ($row) {
                    $colorMap = [
                        'Manajemen Tenant' => 'primary',
                        'Manajemen Sewa Lahan' => 'success',
                        'Manajemen Perijinan Usaha' => 'warning',
                        'Manajemen Pengiklanan' => 'info',
                        'Manajemen Field Trip' => 'secondary',
                        'Manajemen Berita' => 'danger',
                        'Manajemen Laporan Keuangan' => 'dark',
                        'Manajemen Slider' => 'light',
                    ];
                
                    return $row->permissions->map(function ($p) use ($colorMap) {
                        $color = $colorMap[$p->permission_name] ?? 'secondary'; // fallback jika tidak ditemukan
                        return '<span class="badge bg-' . $color . ' me-1">' . $p->permission_name . '</span>';
                    })->implode(' ');
                })
                ->addColumn('action', function ($row) {
                    $td = '<td>';
                    $td .= '<div class="d-flex">';
                    $td .= '<a href="' . route('roles.edit', $row->id) . '" type="button" class="btn btn-sm btn-primary waves-effect waves-light me-1">Edit</a>';
                    $td .= '<form action="' . route('roles.destroy', $row->id) . '" method="POST" style="display:inline;">';
                    $td .= method_field('DELETE');
                    $td .= csrf_field();
                    $td .= '<button type="submit" class="btn btn-sm btn-danger waves-effect waves-light">Hapus</button>';
                    $td .= '</form>';
                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                ->editColumn('created_at', fn ($row) => formatDate($row->created_at))
                ->rawColumns(['permissions', 'action'])
                ->make(true);
        }
        return view('admin.roles.index');
    }

    public function create(Request $request){
        $permissions = Permission::all();
        return view('admin.roles.create')->with('permissions', $permissions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->permissions()->attach($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat!');
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id); // Menyertakan permissions yang dimiliki role
        $permissions = Permission::all(); // Mengambil semua permissions
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'array|exists:permissions,id',
        ]);

        $role->name = $request->name;

        if ($role->isDirty('name')) {
            $role->save(); // akan update updated_at
        } else {
            $role->touch(); // tetap update updated_at walaupun nama tidak berubah
        }

        // Sink permission many-to-many
        $role->permissions()->sync($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus!');
    }
}
