<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    // public function redirectTo()
    // {
    //     if (Auth::user()->is_admin) {
    //         return route('root');
    //     } 
    //     else if (Auth::user()->is_accepted){
    //         return route('profile');
    //     } else {
    //         Auth::logout();
    //         return redirect()->back()->withErrors([
    //             'unverified' => 'Akun Anda belum disetujui. Silakan hubungi admin.',
    //         ]);        
    //     }
    // }
    public function redirectTo()
    {
        return Auth::user()->is_admin ? route('root') : route('profile');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            if (!$user->is_accepted) {
                Auth::logout();
                return redirect()->back()->withErrors([
                    'unverified' => 'Akun Anda belum disetujui. Silakan hubungi admin.',
                ]);
            }

            // Sudah disetujui, lanjut redirect
            return redirect()->intended($this->redirectPath());
        }

        // Kalau gagal login karena email/password salah
        throw ValidationException::withMessages([
            'credentials' => ['Email atau password salah'],
        ]);
    }
    // public function redirectTo(Request $request)
    // {
    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials)) {
    //         $user = Auth::user();

    //         if (!$user->is_accepted) {
    //             Auth::logout();
    //             return redirect()->back()->withErrors([
    //                 'unverified' => 'Akun Anda belum disetujui. Silakan hubungi admin.',
    //             ]);
    //         }

    //         // sudah disetujui, boleh lanjut
    //         return route('profile');;
    //     }
    // }

    // public function redirectTo()
    // {
    //     $user = Auth::user();

    //     if ($user->hasRole('Admin')) {
    //         return route('admin.dashboard');
    //     } elseif ($user->hasRole('Staff')) {
    //         return route('staff.dashboard');
    //     } elseif ($user->hasRole('Tenant')) {
    //         return route('tenant.dashboard');
    //     }

    //     // Default fallback jika role tidak terdaftar
    //     return route('profile');
    // }
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
