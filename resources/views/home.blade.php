@extends('layouts.laravel-default')

@section('title', 'APT PRANOTO')

@section('content')
<div class="">
  
  <div id="carouselExampleSlidesOnly" class="carousel slide vh-100"  style="background-color: rgba(0, 0, 0, 0.5);" data-bs-ride="carousel">
    <div class="carousel-inner hero">
      @forelse ($sliders as $key => $slider)
        <div class="carousel-item vh-100 {{ $key === 0 ? 'active' : '' }}">
          <img 
            src="{{ asset('uploads/' . $slider->documents) }}" 
            class="carousel-home d-block w-100 object-fit-cover" 
            alt="Slider Image {{ $key + 1 }}"
          >
        </div>
      @empty
        <div class="carousel-item vh-100 active">
          <img 
            src="{{ asset('frontend/assets/tes/tes1.jpg') }}" 
            class="carousel-home d-block w-100 object-fit-cover" 
            alt="Default Slider"
          >
        </div>
      @endforelse
    </div>
    <div class="mx-5 d-flex align-items-center justify-content-center text-white vh-100">
      <div class="selling-point">
        <!-- <h2>Selamat datang</h2>
        <h3>Bandar Udara APT Pranoto Kalimantan Timur</h3>
        <div class="ctas">
          @auth
            <button class="cta-main">
              @if (Auth::user()->is_admin)
                <a href="{{ route('root') }}">Dashboard</a>
              @else
                <a href="{{ route('tickets.flights') }}">Pesan Penerbangan</a>
              @endif
            </button>
          @else
            <button class="cta-main">
              <a href="{{ route('tickets.flights') }}">Pesan Penerbangan</a>
            </button>
            <button class="cta-sec">
              <a href="{{ route('register') }}">Daftar</a>
            </button>
          @endauth
        </div> -->
      </div>
    </div>
  </div>
  
  <!-- <section class="front-page min-vh-100 text-white" style="background-color: rgba(0, 0, 0, 0.5);">
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
  </section> -->

  <section class="d-flex gap-5 vh-100 hubud-secondary">
    <img 
      src="{{ asset('frontend/assets/home/sambutan_image.jpg') }}" 
      alt="sambutan"
      class="object-fit-cover" 
    >
    <div class="d-flex flex-column gap-5 justify-content-center me-5">
      <p class="fs-7 lh-base fw-normal">
        "Dalam era yang penuh tantangan ini, di mana teknologi dan informasi berkembang begitu pesat, kita di BLU Kantor UPBU Kelas Kelas I APT. Pranoto Samarinda merasa penting untuk terus beradaptasi. Teknologi telah membawa kita ke Era Revolusi Industri 4.0, yang menuntut kita untuk memanfaatkannya dengan efektif dan efisien. Sejalan dengan semangat revolusi ini, kami berkomitmen untuk memberikan pelayanan yang terbaik kepada masyarakat. Melalui website ini, kami berharap dapat memberikan kemudahan akses informasi seputar kegiatan, tugas dan fungsi BLU Kantor UPBU Kelas I APT. Pranoto Samarinda. Kami mengundang anda untuk menjelajahi situs web kami, mendapatkan informasi yang berguna, dan memberikan masukan yang konstruktif. Semoga dengan kehadiran situs ini, kita dapat meningkatkan kualitas interaksi, informasi, dan komunikasi antara BLU Kantor UPBU Kelas I APT. Pranoto Samarinda dengan masyarakat."
      </p>
      <div class="fw-bold d-flex flex-column gap-0">
        <span>Maeka Rindra Hariyanto, SE., M.Si </span>
        <span class="fw-normal fst-italic">Kepala BLU Kantor UPBU Kelas I APT. Pranoto Samarinda</span>
      </div>
    </div>
  </section>


    <section class="classes py-5 bg-black text-white">
      <div class="container">
        <div class="mb-4">
          <h2>Topik Utama</h2>
        </div>

        <div class="row g-4">
          @foreach($topikUtama as $news)
            <div class="col-md-4">
              <a href="{{route('showNews' , $news->slug)}}" class="text-decoration-none text-white ">
                <div class="ratio ratio-4x3 overflow-hidden pilates">
                  <img 
                    src="{{ asset('uploads/' . $news->image) }}" 
                    class="w-100"
                    style="object-position: center center; object-fit: cover;" 
                    alt="{{ $news->title }}"
                  >
                </div>
                <div class="">
                  <p class="mt-2 email">{{ $news->title }}</p>
                </div>
              </a>
            </div>
          @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
          <a href="{{route('berita')}}" class="other-news d-flex align-items-center text-white text-decoration-none ">
            <span class="fs-7">Lihat Berita Lainnya</span>
            <i class="bx bx-right-arrow-alt fs-6"></i>
          </a>
        </div>
      </div>
    </section>


  <!-- <section class="classes py-5 bg-black text-white">
    <div class="d-flex py-0">
      <h2>Topik Utama</h2>
    </div>
    <div class="videos fw-normal fs-5 d-flex gap-5">
      <a href="#" class="pilates d-flex flex-column gap-2 text-decoration-none text-white">
        <img 
        src="{{ asset('frontend/assets/tes/tes1.jpg') }}" 
        class="d-block w-100 object-fit-cover" 
        alt="..."
        >  
        <p>Judul Berita 1</p>
      </a>
      <a href="#" class="pilates d-flex flex-column gap-2 text-decoration-none text-white">
        <img 
        src="{{ asset('frontend/assets/tes/tes2.jpg') }}" 
        class="d-block w-100 object-fit-cover" 
        alt="..."
        >  
        <p>Judul Berita 2</p>
      </a>
      <a href="#" class="pilates d-flex flex-column gap-2 text-decoration-none text-white">
        <img 
        src="{{ asset('frontend/assets/tes/tes3.jpg') }}" 
        class="d-block w-100 object-fit-cover" 
        alt="..."
        >  
        <p>Judul Berita 3</p>
      </a>
    </div>
    <div class="d-flex justify-content-center mt-4">
      <a href="#" class="other-news d-flex align-items-center text-white text-decoration-none ">
        <span class="fs-7">Lihat Berita Lainnya</span>
        <i class="bx bx-right-arrow-alt fs-6"></i>
      </a>
    </div>
  </section> -->

  
@endsection
