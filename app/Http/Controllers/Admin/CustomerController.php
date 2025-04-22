<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function toggleStaff(User $user)
    {
        if ($user->is_staff) {
            $user->is_staff = false;
            $user->roles()->detach(); 
        } else {
            $user->is_staff = true;
            $roleStaff = Role::where('name', 'staff')->first();
            if ($roleStaff) {
                $user->roles()->attach($roleStaff->id);
            }
        }

        $user->save();

        return redirect()->route('customers.show', $user->id)
            ->with('success', 'Status staff berhasil diperbarui.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();

        return view('customers.show', compact('user', 'roles', 'userRoles'));
    }

    public function verify(User $user)
    {
        $user->update(['is_accepted' => 1]);
        return back()->with('success', 'User berhasil diverifikasi.');
    }

    public function unverify(User $user)
    {
        $user->update(['is_accepted' => 0]);
        return back()->with('success', 'Verifikasi user dibatalkan.');
    }

    public function toggleRole(Request $request, User $user)
    {
        $roleId = $request->input('role_id');

        $hasRole = $user->roles()->where('role_id', $roleId)->exists();

        if ($hasRole) {
            $user->roles()->detach($roleId); 
        } else {
            $user->roles()->attach($roleId); 
        }

        return back();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $acceptedData = User::where('is_accepted', 1)->withCount('tickets');
            $unacceptedData = User::where('is_accepted', 0)->withCount('tickets');
            
            $data = User::whereIn('is_accepted', [0, 1])->withCount('tickets');
            if ($request->has('search') && $request->search['value']) {
                $search = $request->search['value'];
                $data->where(function($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                });
            }
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->setRowClass(fn ($row) => 'align-middle')
                ->addColumn('action', function ($row) {
                    $td = '<td>';
                    $td .= '<div class="d-flex">';
                    $td .= '<a href="' . route('customers.show', $row->id) . '" type="button" class="btn btn-sm  btn-primary waves-effect waves-light me-1">Lihat</a>';
                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                ->editColumn('tickets_count', function ($row) {
                    return '<span class="badge badge-pill badge-soft-info font-size-14">' . $row->tickets_count . '</span>';
                })
                ->editColumn('created_at', fn ($row) => formatDate($row->created_at))
                ->rawColumns(['action', 'tickets_count', 'is_accepted'])
                ->make(true);
        }
        return view('admin.customers.index');
    }


    public function show(Request $request, User $user)
    {
        $user->load('tickets');
        $roles = Role::with('permissions')->get(); 
        $userRoles = $user->roles->pluck('id')->toArray();
        $colorMap = [
            'Manajemen Tenant' => 'primary',
            'Manajemen Sewa Lahan' => 'success',
            'Manajemen Perijinan Usaha' => 'warning',
            'Manajemen Pengiklanan' => 'info',
            'Manajemen Field Trip' => 'secondary',
            'Manajemen Pergudangan' => 'danger',
            'Manajemen Laporan Keuangan' => 'dark',
            'Manajemen Slider' => 'light',
        ];

        if ($request->ajax()) {
            return Datatables::of($user->tickets)->addIndexColumn()
                ->setRowClass(fn ($row) => 'align-middle')
                ->addColumn('action', function ($row) {
                    $td = '<td>';
                    $td .= '<div class="d-flex justify-content-center">';
                    $td .= '<button data-id="' . $row->id . '" type="button" class="btn btn-sm btn-outline-primary waves-effect waves-light me-1 cancel-btn" disabled>Chanage Status</button>';
                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                ->addColumn('status', function ($row) {

                    return '<span class="badge badge-pill badge-soft-' . getStatusColor($row->status) . ' font-size-14">' . $row->status . '</span>';
                })
                ->editColumn('flight_info', function ($row) {
                    $td = '<td>';
                    $td .= '<div class="">';
                    $td .= '<p class="fw-bold">' . __('translation.flight.flight_number') . ': <span class="fw-normal">' . $row->flight->flight_number . '</span></p>';
                    $td .= '<p class="fw-bold">' . __('translation.flight.plane_code') . ': <span class="fw-normal">' . $row->flight->plane->code . '</span></p>';
                    $td .= '<p class="fw-bold">' . __('translation.flight.airline') . ': <span class="fw-normal">' . $row->flight->airline->name . '</span></p>';
                    $td .= '<p class="fw-bold">' . __('translation.flight.price') . ': <span class="fw-normal">' . formatPrice($row->flight->price) . '</span></p>';
                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                ->editColumn('route', function ($row) {
                    $td = '<td>';
                    $td .= '<div class="">';
                    $td .= '<p class="fw-bold">' . __('translation.flight.origin') . ': <span class="fw-normal">' . airportName($row->flight->origin->name) . '</span></p>';
                    $td .= '<p class="fw-bold">' . __('translation.flight.destination') . ': <span class="fw-normal">' . airportName($row->flight->destination->name) . '</span></p>';
                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                ->editColumn('time', function ($row) {
                    $td = '<td>';
                    $td .= '<div class="">';
                    $td .= '<p class="fw-bold">' . __('translation.flight.departure') . ': <span class="fw-normal">' . formatDateWithTimezone($row->flight->departure) . '</span></p>';
                    $td .= '<p class="fw-bold">' . __('translation.flight.arrival') . ': <span class="fw-normal">' . formatDateWithTimezone($row->flight->arrival) . '</span></p>';
                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                ->rawColumns(['flight_info', 'route', 'time', 'action', 'status'])
                ->make(true);
        }

        return view('admin.customers.show', compact('user', 'roles', 'colorMap', 'userRoles'));
    }
    
    public function updateRole(Request $request, User $user)
    {
        // Ambil semua ID role yang dipilih (bernilai on)
        $selectedRoles = array_keys($request->roles ?? []);
        $user->roles()->sync($selectedRoles);

        return redirect()->route('customers.show', $user)->with('success', 'Role berhasil diperbarui.');
    }
}
