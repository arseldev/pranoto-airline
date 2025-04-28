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
        // Ambil data berdasarkan filter yang dipilih
        $jenis_filter = $request->input('jenis_filter', 'bulan');
        $filterTahun = $request->input('tahun', now()->year);
        
        // Ambil data berdasarkan filter tahun yang dipilih untuk grafik pie
        $filterTahunPie = $request->input('tahun_pie', now()->year);
        
        // Ambil nilai anggaran dari request dan konversi ke integer
        $anggaran = $request->has('anggaran') ? (int)$request->input('anggaran') : null;
        
        // Debug nilai anggaran yang diterima
        // \Log::info('Nilai anggaran yang diterima: ' . $request->input('anggaran'));
        // \Log::info('Nilai anggaran setelah konversi: ' . $anggaran);
        
        // Flag untuk menentukan apakah grafik pie perlu ditampilkan
        $showPieChart = $anggaran !== null && $anggaran > 0;
        
        // Ambil semua tahun yang tersedia di database untuk dropdown
        $years = Finance::selectRaw('YEAR(date) as year')
                        ->distinct()
                        ->orderBy('year', 'desc')
                        ->get()
                        ->pluck('year');
        
        // Jika tidak ada tahun yang tersedia, gunakan tahun sekarang
        if ($years->isEmpty()) {
            $years = collect([now()->year]);
        }
        
        // Mendapatkan data pemasukan per bulan atau per tahun
        if ($jenis_filter == 'bulan') {
            // Filter per bulan dalam satu tahun tertentu
            $data = Finance::whereYear('date', $filterTahun)
                            ->where('flow_type', 'in') // hanya pemasukan
                            ->selectRaw('MONTH(date) as bulan, SUM(amount) as total_pemasukan')
                            ->groupBy('bulan')
                            ->orderBy('bulan')
                            ->get();
            
            // Untuk bulan, label bulan bisa diatur sesuai nama bulan
            $labels = $data->map(function ($item) {
                return \Carbon\Carbon::createFromFormat('m', $item->bulan)->format('F');
            });
            $dataPemasukan = $data->pluck('total_pemasukan');
        } else {
            // Filter per tahun untuk semua tahun yang ada
            $data = Finance::where('flow_type', 'in')
                            ->selectRaw('YEAR(date) as tahun, SUM(amount) as total_pemasukan')
                            ->groupBy('tahun')
                            ->orderBy('tahun')
                            ->get();
            
            // Untuk tahun, label adalah semua tahun yang ada
            $labels = $data->pluck('tahun');
            $dataPemasukan = $data->pluck('total_pemasukan');
        }
        
        // Data untuk grafik pie: anggaran vs pengeluaran dalam tahun yang dipilih
        $totalPemasukan = Finance::whereYear('date', $filterTahunPie)
                            ->where('flow_type', 'in')
                            ->sum('amount');
        
        $totalPengeluaran = $showPieChart ? Finance::whereYear('date', $filterTahunPie)
                            ->where('flow_type', 'out')
                            ->sum('amount') : 0;
        

        $visitors = Visitor::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
        ->whereDate('created_at', '>=', now()->subDays(6)) // 7 hari ke belakang (hari ini + 6 hari lalu)
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();
    
        // Siapkan data untuk Chart.js
        $dates = $visitors->pluck('date');
        $totals = $visitors->pluck('total');

        $totalAirline = Airline::count();
        $totalCustomer = User::whereIsAdmin(0)->count();
        $totalPlane = Plane::count();
        $totalAirport = Airport::count();
        $totalFlight = Flight::count();
        $totalTicket = Ticket::count();

        // get last 10 flights
        $lastFlights = Flight::orderBy('id', 'desc')->take(10)->get();

        // get active ariline by number of flights
        $activeAirlines = Airline::query()
            ->withCount('flights')
            ->withCount('planes')
            ->orderBy('flights_count', 'desc')
            ->take(6)
            ->get();

        // CHARTS DATA CONFIG
        // get status of flights
        $flightStatusChart = DB::table('flights')
            ->orderBy('status', 'desc')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
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
            'data', 'dates', 'totals', 'labels', 'dataPemasukan', 'totalPemasukan', 'totalPengeluaran', 
            'years', 'filterTahun', 'filterTahunPie', 'jenis_filter', 
            'anggaran', 'showPieChart')
        );
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
