<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use App\Models\Plane;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Finance;
use App\Models\BudgetExpense;
use App\Models\Slider;
use App\Models\Visitor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (view()->exists('template.' . $request->path())) {
            return view('template.' . $request->path());
        }
        return abort(404);
    }
    public function home()
    {
        return view('home');
    }

    public function root(Request $request)
    {
        // Ambil semua tahun unik dari tabel finances
        $years = Finance::selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        $filterTahun = $request->get('tahun', date('Y'));
        $filterTahunPie = $request->get('tahun_pie', date('Y'));
        $jenis_filter = $request->get('jenis_filter', 'bulan');

        // 1. DATA GRAFIK BAR (PEMASUKAN)
        $labels = [];
        $dataPemasukan = [];

        if ($jenis_filter == 'bulan') {
            // Label bulan
            $labels = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            // Inisialisasi data 0 untuk 12 bulan
            $dataPemasukan = array_fill(0, 12, 0);

            // Query pemasukan per bulan di tahun terpilih
            $pemasukanPerBulan = Finance::selectRaw('MONTH(date) as month, SUM(amount) as total')
                ->where('flow_type', 'in')
                ->whereYear('date', $filterTahun)
                ->groupBy('month')
                ->get();

            // Masukkan ke array
            foreach ($pemasukanPerBulan as $item) {
                $index = $item->month - 1; // Index bulan (0-11)
                $dataPemasukan[$index] = (float) $item->total;
            }
        } else {
            // jenis_filter == tahun

            // Ambil semua tahun dari finances flow_type = in
            $tahunRange = Finance::selectRaw('YEAR(date) as year')
                ->where('flow_type', 'in')
                ->distinct()
                ->orderBy('year')
                ->pluck('year')
                ->toArray();

            foreach ($tahunRange as $year) {
                $labels[] = $year;

                $total = Finance::where('flow_type', 'in')
                    ->whereYear('date', $year)
                    ->sum('amount');

                $dataPemasukan[] = (float) $total;
            }
        }

        // 2. DATA GRAFIK PIE (ANGGARAN VS PENGELUARAN)
        $anggaran = Finance::where('flow_type', 'budget')
            ->whereYear('date', $filterTahunPie)
            ->sum('amount');

        $totalPengeluaran = BudgetExpense::whereHas('finance', function ($query) use ($filterTahunPie) {
            $query->whereYear('date', $filterTahunPie);
        })->sum('amount');

        $showPieChart = ($anggaran > 0 || $totalPengeluaran > 0);

        // 3. DATA KUNJUNGAN (VISITORS)
        $visitors = Visitor::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->whereDate('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $dates = $visitors->pluck('date');
        $totals = $visitors->pluck('total');

        // 4. DATA SUMMARY
        $totalAirline = Airline::count();
        $totalCustomer = User::whereIsAdmin(0)->count();
        $totalPlane = Plane::count();
        $totalAirport = Airport::count();
        $totalFlight = Flight::count();
        $totalTicket = Ticket::count();

        $lastFlights = Flight::orderBy('id', 'desc')->take(10)->get();

        $activeAirlines = Airline::query()
            ->withCount('flights')
            ->withCount('planes')
            ->orderBy('flights_count', 'desc')
            ->take(6)
            ->get();

        // 5. CHART STATUS FLIGHTS
        $flightStatusChart = DB::table('flights')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderBy('status', 'desc')
            ->get()
            ->map(function ($item) {
                switch (trim($item->status)) {
                    case 0:
                        $item->label = "Land";
                        $item->color = "#ea868f";
                        break;
                    case 1:
                        $item->label = "Take Off";
                        $item->color = "#20c997";
                        break;
                }
                return (array) $item;
            })->toArray();

        // 6. COMPACT DATA KE VIEW
        $data = [
            'totalAirline'      => $totalAirline,
            'totalPlane'        => $totalPlane,
            'totalAirport'      => $totalAirport,
            'totalFlight'       => $totalFlight,
            'totalTicket'       => $totalTicket,
            'totalCustomer'     => $totalCustomer,
            'lastFlights'       => $lastFlights,
            "activeAirlines"    => $activeAirlines,
            "flightStatusChart" => $flightStatusChart,
        ];

        return view('admin.index', compact(
            'data', 'dates', 'totals', 'years', 'filterTahun', 'filterTahunPie',
            'jenis_filter', 'labels', 'dataPemasukan',
            'anggaran', 'totalPengeluaran', 'showPieChart'
        ));
    }


    public function storeTempFile(Request $request)
    {

        $path = storage_path('tmp/uploads');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function deleteTempFile(Request $request)
    {
        $path = storage_path('tmp/uploads');
        if (file_exists($path . '/' . $request->fileName)) {
            unlink($path . '/' . $request->fileName);
        }
    }

    /*Language Translation*/
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }

    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $user = User::find($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');

        if ($request->file('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = public_path('/images/');
            $avatar->move($avatarPath, $avatarName);
            $user->avatar = '/images/' . $avatarName;
        }

        $user->update();
        if ($user) {
            Session::flash('message', 'User Details Updated successfully!');
            Session::flash('alert-class', 'alert-success');
            return response()->json([
                'isSuccess' => true,
                'Message' => "User Details Updated successfully!"
            ], 200); // Status code here
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return response()->json([
                'isSuccess' => true,
                'Message' => "Something went wrong!"
            ], 200); // Status code here
        }
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            return response()->json([
                'isSuccess' => false,
                'Message' => "Your Current password does not matches with the password you provided. Please try again."
            ], 200); // Status code
        } else {
            $user = User::find($id);
            $user->password = Hash::make($request->get('password'));
            $user->update();
            if ($user) {
                Session::flash('message', 'Password updated successfully!');
                Session::flash('alert-class', 'alert-success');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Password updated successfully!"
                ], 200); // Status code here
            } else {
                Session::flash('message', 'Something went wrong!');
                Session::flash('alert-class', 'alert-danger');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Something went wrong!"
                ], 200); // Status code here
            }
        }
    }
}
