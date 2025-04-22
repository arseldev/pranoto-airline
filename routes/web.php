<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{
    HomeController,
    AirlineController,
    AirportController,
    CustomerController,
    PlaneController,
    FlightController,
    ProfileController,
    TicketController,
    RoleController,
};
use App\Http\Controllers\Staff_User\{
    NewsController,
    FieldTripController,
    LaporanKeuanganController,
    PengiklananController,
    PergudanganController,
    PerijinanUsahaController,
    SewaLahanController,
    SliderController,
    TenantController,
};

use App\Http\Controllers\SandboxController;
use App\Http\Controllers\SidebarControler;

Auth::routes();

Route::group(["prefix" => 'dashboard'], function () {
    Route::group(['middleware' => 'auth'], function () {
        /* ================== USER ROUTES ================== */
        // Tenant User Routes
        Route::get('/tenant', [TenantController::class, 'indexUser'])->name('tenant.index');
        Route::get('/tenant/create', [TenantController::class, 'create'])->name('tenant.create');
        Route::post('/tenant/store', [TenantController::class, 'store'])->name('tenant.store');
        Route::delete('/tenant/{id}', [TenantController::class, 'destroy'])->name('tenant.destroy');
        Route::get('/tenant', [TenantController::class, 'indexUser'])->name('tenant.index');
        
        // Rental User Routes
        Route::get('/sewa', [SewaLahanController::class, 'indexUser'])->name('sewa.index');
        Route::get('/sewa/create', [SewaLahanController::class, 'create'])->name('sewa.create');
        Route::post('/sewa/store', [SewaLahanController::class, 'store'])->name('sewa.store');
        Route::delete('/sewa/{id}', [SewaLahanController::class, 'destroy'])->name('sewa.destroy');
        
        // Perijinan User Routes
        Route::get('/perijinan', [PerijinanUsahaController::class, 'indexUser'])->name('perijinan.index');
        Route::get('/perijinan/create', [PerijinanUsahaController::class, 'create'])->name('perijinan.create');
        Route::post('/perijinan/store', [PerijinanUsahaController::class, 'store'])->name('perijinan.store');
        Route::delete('/perijinan/{id}', [PerijinanUsahaController::class, 'destroy'])->name('perijinan.destroy');
        
        // Pengiklanan User Routes
        Route::get('/pengiklanan', [PengiklananController::class, 'indexUser'])->name('pengiklanan.index');
        Route::get('/pengiklanan/create', [PengiklananController::class, 'create'])->name('pengiklanan.create');
        Route::post('/pengiklanan/store', [PengiklananController::class, 'store'])->name('pengiklanan.store');
        Route::delete('/pengiklanan/{id}', [PengiklananController::class, 'destroy'])->name('pengiklanan.destroy');
        
        // Pengiklanan User Routes
        Route::get('/fieldtrip', [FieldTripController::class, 'indexUser'])->name('fieldtrip.index');
        Route::get('/fieldtrip/create', [FieldTripController::class, 'create'])->name('fieldtrip.create');
        Route::post('/fieldtrip/store', [FieldTripController::class, 'store'])->name('fieldtrip.store');
        Route::delete('/fieldtrip/{id}', [FieldTripController::class, 'destroy'])->name('fieldtrip.destroy');

        Route::middleware(['auth', 'staff'])->group(function () {
            // News Staff Routes
            Route::get('staff/berita', [NewsController::class, 'index'])->name('berita.staffIndex');
            Route::get('staff/berita/create', [NewsController::class, 'create'])->name('berita.create');
            Route::get('staff/berita/{slug}', [NewsController::class, 'show'])->name('berita.show');
            Route::post('staff/berita/store', [NewsController::class, 'store'])->name('berita.store');
            Route::delete('staff/berita/{id}/destroy', [NewsController::class, 'destroy'])->name('berita.destroy');
            Route::patch('staff/berita/{id}/toggle-headline', [NewsController::class, 'toggleHeadline'])->name('berita.toggleHeadline');
            Route::patch('staff/berita/{id}/toggle-publish', [NewsController::class, 'togglePublish'])->name('berita.togglePublish');
            // Route::patch('staff/slider/{id}/toggle-visibility-footer', [NewsController::class, 'toggleVisibilityFooter'])->name('slider.toggleVisibilityFooter');
            
            // Tenant Staff Routes
            Route::get('staff/tenant', [TenantController::class, 'index'])->name('tenant.staffIndex');
            Route::get('staff/tenant/{id}', [TenantController::class, 'show'])->name('tenant.show');
            Route::patch('staff/tenant/{id}/approve', [TenantController::class, 'approve'])->name('tenant.approve');
            Route::patch('staff/tenant/{id}/reject', [TenantController::class, 'reject'])->name('tenant.reject');

            // Rental Staff Routes
            Route::get('staff/sewa', [SewaLahanController::class, 'index'])->name('sewa.staffIndex');
            Route::get('staff/sewa/{id}', [SewaLahanController::class, 'show'])->name('sewa.show');
            Route::patch('staff/sewa/{id}/approve', [SewaLahanController::class, 'approve'])->name('sewa.approve');
            Route::patch('staff/sewa/{id}/reject', [SewaLahanController::class, 'reject'])->name('sewa.reject');
            
            // Perijinan Staff Routes
            Route::get('staff/perijinan', [PerijinanUsahaController::class, 'index'])->name('perijinan.staffIndex');
            Route::get('staff/perijinan/{id}', [PerijinanUsahaController::class, 'show'])->name('perijinan.show');
            Route::patch('staff/perijinan/{id}/approve', [PerijinanUsahaController::class, 'approve'])->name('perijinan.approve');
            Route::patch('staff/perijinan/{id}/reject', [PerijinanUsahaController::class, 'reject'])->name('perijinan.reject');
            
            // Pengiklanan Staff Routes
            Route::get('staff/pengiklanan', [PengiklananController::class, 'index'])->name('pengiklanan.staffIndex');
            Route::get('staff/pengiklanan/{id}', [PengiklananController::class, 'show'])->name('pengiklanan.show');
            Route::patch('staff/pengiklanan/{id}/approve', [PengiklananController::class, 'approve'])->name('pengiklanan.approve');
            Route::patch('staff/pengiklanan/{id}/reject', [PengiklananController::class, 'reject'])->name('pengiklanan.reject');
            
            // Fieldtrip Staff Routes
            Route::get('staff/fieldtrip', [FieldTripController::class, 'index'])->name('fieldtrip.staffIndex');
            Route::get('staff/fieldtrip/{id}', [FieldTripController::class, 'show'])->name('fieldtrip.show');
            Route::patch('staff/fieldtrip/{id}/approve', [FieldTripController::class, 'approve'])->name('fieldtrip.approve');
            Route::patch('staff/fieldtrip/{id}/reject', [FieldTripController::class, 'reject'])->name('fieldtrip.reject');
            
            // Slider Staff Routes
            Route::get('staff/slider', [SliderController::class, 'index'])->name('slider.staffIndex');
            Route::get('staff/slider/create', [SliderController::class, 'create'])->name('slider.create');
            Route::post('staff/slider/store', [SliderController::class, 'store'])->name('slider.store');
            Route::delete('staff/slider/{id}/destroy', [SliderController::class, 'destroy'])->name('slider.destroy');
            Route::patch('staff/slider/{id}/toggle-visibility-home', [SliderController::class, 'toggleVisibilityHome'])->name('slider.toggleVisibilityHome');
            Route::patch('staff/slider/{id}/toggle-visibility-footer', [SliderController::class, 'toggleVisibilityFooter'])->name('slider.toggleVisibilityFooter');
            
            // Report Staff Routes
            Route::get('staff/keuangan', [LaporanKeuanganController::class, 'index'])->name('keuangan.staffIndex');
        });


        //profile
        Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');


        //tickets
        Route::get('tickets/show-flights', [TicketController::class, 'showFlights'])->name('tickets.flights');
        Route::get('tickets/user-tickets', [TicketController::class, 'userTickets'])->name('tickets.userTickets');
        Route::post('tickets/book', [TicketController::class, 'book'])->name('tickets.book');
        Route::post('tickets/cancel-flight', [TicketController::class, 'cancel'])->name('tickets.cancel');



        /* ================== ADMIN ROUTES ================== */
        Route::group(['middleware' => 'admin'], function () {
            Route::get('/', [HomeController::class, 'root'])->name('root');

            //get count of tickets
            Route::get('/ticket-status-count', [SidebarControler::class, 'ticketStatusCount'])->name('ticketStatusCount');

            //airlines
            Route::resource("airlines", AirlineController::class);

            //planes
            Route::resource("planes", PlaneController::class)->except('show');

            //airports
            Route::resource("airports", AirportController::class)->except('show');

            //flights
            Route::get("flights/get-planes-by-airline", [FlightController::class, 'getPlanesByAirline'])->name('flights.getPlanesByAirline');
            Route::resource("flights", FlightController::class)->except('show');

            //tickets
            Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');
            Route::post('tickets/change-status/{ticket}', [TicketController::class, 'changeStatus'])->name('tickets.changeStatus');

            //customers
            Route::get("customers", [CustomerController::class, "index"])->name('customers.index');
            Route::get("customers/{user}", [CustomerController::class, "show"])->name('customers.show');
            Route::post('customers/{user}', [CustomerController::class, 'toggleRole'])->name('customers.toggle-role');
            Route::post('customers/{user}/verify', [CustomerController::class, 'verify'])->name('customers.verify');
            Route::post('customers/{user}/unverify', [CustomerController::class, 'unverify'])->name('customers.unverify');
            Route::post('customers/{user}/toggle-staff', [CustomerController::class, 'toggleStaff'])->name('customers.toggleStaff');
            Route::put('customers/{user}/update-role', [CustomerController::class, 'updateRole'])->name('customers.updateRole');
            Route::get('customers/{user}/edit-role', [CustomerController::class, 'editRole'])->name('customers.editRole');
            Route::resource('customers', CustomerController::class);

            //roles
            Route::resource('roles', RoleController::class);
        });
    });
    Route::middleware(['auth'])->group(function () {
        
        
        
        // Sewa Lahan Routes
        Route::get('/sewa', [SewaLahanController::class, 'index'])->name('sewa.index');
        
        // Perijinan Usaha Routes
        Route::get('/izin', [PerijinanUsahaController::class, 'index'])->name('izin.index');
        
        // Pengiklanan Routes
        Route::get('/iklan', [PengiklananController::class, 'index'])->name('iklan.index');
        
        // Field Trip Routes
        Route::get('/fieldtrip', [FieldTripController::class, 'index'])->name('fieldtrip.index');
        
        // Pergudangan Routes
        Route::get('/gudang', [PergudanganController::class, 'index'])->name('gudang.index');
        
        // Laporan Keuangan Routes
        Route::get('/keuangan', [LaporanKeuanganController::class, 'index'])->name('keuangan.index');
        
        // Slider Routes
        Route::get('/slider', [SliderController::class, 'index'])->name('slider.index');
    });
});


Route::get('/', [HomeController::class, 'home'])->name('home');

//Language Translation
Route::get('/index/{locale}', [HomeController::class, 'lang']);

Route::post('/store-temp-file', [HomeController::class, 'storeTempFile'])->name('storeTempFile');
Route::post('/delete-temp-file', [HomeController::class, 'deleteTempFile'])->name('deleteTempFile');

Route::get('/get-random-customer', [SandboxController::class, 'randomCustomer'])->name('randomCustomer');

//render files inside views/template folder
Route::get('{any}', [HomeController::class, 'index'])->name('index');
