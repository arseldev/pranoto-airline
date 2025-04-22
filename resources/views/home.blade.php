@extends('layouts.laravel-default')

@section('title', 'Beranda')

@section('content')
<div class="">
  <section class="front-page min-vh-100 text-white" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="mx-5">
      <img class="hero" src="{{ asset('frontend/assets/hero.png') }}" alt="meditation" autoplay />
      <video muted autoplay loop class="hero" src="{{ asset('frontend/assets/video.mp4') }}"></video>
      <nav class="d-flex justify-content-between fixed-top px-5 py-4" id="navbar">
        <div class="logo">
          <img src="{{ asset('frontend/assets/logo.png') }}" alt="mind & body" style="width: 12rem" />
        </div>
        <div class="">
          <div class="fs-6 d-flex gap-5">
            <a href="/" class="pe-auto text-decoration-none navigation">Home</a>
            <a href="#" class="pe-auto text-decoration-none navigation">Informasi Publik</a>
            <a href="#" class="pe-auto text-decoration-none navigation">Informasi</a>
            <div class="dropdown">
              <a class="dropdown-toggle border-0 m-0 p-0 text-decoration-none navigation" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                Layanan
              </a>
              <ul class="dropdown-menu bg-transparent m-0 mt-3 border-0" aria-labelledby="dropdownMenuLink">
                <li><a class="dropdown-item bg-dark text-white mt-2" href="#">Tenant</a></li>
              </ul>
            </div>

            @auth
              @if (Auth::user()->is_admin)
                <a href="{{ route('root') }}" class="pe-auto text-decoration-none navigation">Dashboard</a>
              @else
                <a href="{{ route('root') }}/profile" class="pe-auto text-decoration-none navigation">Dashboard</a>
              @endif
            @else
              <a href="{{ route('login') }}" class="pe-auto text-decoration-none navigation">Masuk</a>
            @endauth
          </div>
        </div>
      </nav>
      <div class="mx-5">
        <div class="selling-point">
          <h2>Let your mind breathe.</h2>
          <h3>The world is a book and those who do not travel read only one page.</h3>
          <div class="ctas">
            @auth
              <button class="cta-main">
                @if (Auth::user()->is_admin)
                  <a href="{{ route('root') }}">Dashboard</a>
                @else
                  <a href="{{ route('tickets.flights') }}">Book A Flight</a>
                @endif
              </button>
            @else
              <button class="cta-main">
                <a href="{{ route('tickets.flights') }}">Book A Flight</a>
              </button>
              <button class="cta-sec">
                <a href="{{ route('register') }}">Sign up</a>
              </button>
            @endauth
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="classes">
    <div class="classes-description">
      <h2>Placees waiting for you</h2>
      <h3>It's time to heal your mind and body</h3>
    </div>
    <div class="videos">
      <div class="pilates">
        <h3>Pilates</h3>
        <video muted loop class="video" src="{{ asset('frontend/assets/travel-4.mp4') }}"></video>
      </div>
      <div class="yoga">
        <h3>Yoga</h3>
        <video muted loop class="video" src="{{ asset('frontend/assets/travel-2.mp4') }}"></video>
      </div>
      <div class="meditation">
        <h3>Meditation</h3>
        <video muted loop class="video" src="{{ asset('frontend/assets/travel-3.mp4') }}"></video>
      </div>
    </div>
  </section>

  <section class="about">
    <div class="our-story">
      <h2>About Us</h2>
      <p>
        Always a student, Janet has immersed herself in the ancient practices
        of yoga for over thirty years. A global yoga teacher, she shares the
        teachings from the heart. Through curiosity, devotion, and dedication
        she creates a unique approach to living yoga.
      </p>
    </div>
    <img src="{{ asset('frontend/assets/our-story.jpg') }}" alt="our-story" />
  </section>
</div>
@endsection