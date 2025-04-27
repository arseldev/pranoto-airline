@extends('layouts.laravel-default')

@section('title', 'Profil Bandara | APT PRANOTO')

@section('content')
<section class="py-5">
  <div class="container">
  <h2 class="mb-4 fw-bold">Struktur Organisasi Bandara A.P.T. Pranoto</h2>
    <div class="">
      <img
        class="w-100 object-fit-cover" 
        src="{{asset('frontend/assets/struktur-organisasi.jpg')}}" 
        alt="">
    </div>
  </div>
</section>
@endsection
