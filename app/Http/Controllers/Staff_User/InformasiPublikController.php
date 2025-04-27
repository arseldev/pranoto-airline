<?php

namespace App\Http\Controllers\Staff_User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\PublicInformation;

class InformasiPublikController extends Controller
{
    public function index()
    {
        $publicInformation = PublicInformation::latest()->get();
        return view('user_staff.informasi-publik.index', compact('publicInformation'));
    }
    public function show($id)
    {
        $publicInformation = PublicInformation::where('id', $id)->firstOrFail();
        return view('user_staff.informasi-publik.show', compact('publicInformation'));
    }
}
