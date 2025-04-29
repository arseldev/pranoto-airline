@extends('layouts.laravel-default')

@section('title', 'Profil Bandara | APT PRANOTO')

@section('content')
<section class="pb-5">
  <div class="container">
  <h2 class="mb-4 fw-bold fs-1">Struktur Organisasi Bandara A.P.T. Pranoto</h2>
    <div class="container">
      <img
        class="object-fit-cover" style="width: 100%"
        src="{{asset('frontend/assets/struktur-organisasi.jpg')}}" 
        alt="">
    </div>
  </div>
</section>
@endsection
