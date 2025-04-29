<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="{{ asset('frontend/style.css') }}">
    <title>@yield('title', 'APT Pranoto')</title>
    @stack('styles')
    <style>
      .content-wrapper {
        margin-top: 80px;
      }
    </style>
</head>

<body>
    <main style="overflow-x: hidden;">
        <nav class="navbar navbar-expand-lg  px-md-5 py-md-4 scrolled" id="navbar">
            <div class="container-fluid">
                <!-- Logo -->
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('frontend/assets/logo.png') }}" alt="mind & body" style="width: 7rem;" />
                </a>

                <!-- Toggler -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                    aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class='bx bx-menu'></i>
                </button>

                <!-- Navbar Items -->
                <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
                    <ul class="navbar-nav gap-lg-4 text-center text-lg-start">
                        @foreach ($menuItems['header'] as $item)
                            @if (isset($item['dropdown']))
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle navigation" href="#"
                                        id="dropdownMenuLink{{ $loop->index }}" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ $item['name'] }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink{{ $loop->index }}">
                                        @foreach ($item['dropdown'] as $dropdownItem)
                                            <li><a class="dropdown-item"
                                                    href="{{ $dropdownItem['route'] }}">{{ $dropdownItem['name'] }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link navigation" href="{{ $item['route'] }}">{{ $item['name'] }}</a>
                                </li>
                            @endif
                        @endforeach

                        @auth
                            <li class="nav-item">
                                @if (Auth::user()->is_admin)
                                    <a href="{{ route('root') }}" class="nav-link navigation">Dashboard</a>
                                @else
                                    <a href="{{ route('root') }}/profile" class="nav-link navigation">Dashboard</a>
                                @endif
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link navigation">Masuk</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
        <div class="content-wrapper">
          @yield('content')
        </div>
        <footer class="bg-black text-white">
            <div class="footer row">
                <div id="carouselExampleSlidesOnly" class="carousel slide col d-flex align-items-end "
                    data-bs-ride="carousel">
                    <div class="carousel-inner h-75">
                        @forelse ($footerSliders as $key => $slider)
                            <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                <img src="{{ asset('uploads/' . $slider->documents) }}"
                                    class="carousel-footer d-block w-100 object-fit-cover"
                                    alt="Slider Image {{ $key + 1 }}">
                            </div>
                        @empty
                            <div class="carousel-item active">
                                <img src="{{ asset('frontend/assets/tes/tes1.jpg') }}"
                                    class="carousel-footer d-block w-100 object-fit-cover" alt="Default Slider">
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="col align-content-end">
                    @if ($topikUtama->isNotEmpty())
                        <section class="text-white">
                            <div class="container">
                                <div class="row">
                                    @foreach ($topikUtama as $news)
                                        <div class="col-md-4">
                                            <a href="{{ route('showNews', $news->slug) }}"
                                                class="text-decoration-none text-white">
                                                <div class="ratio ratio-4x3 overflow-hidden pilates">
                                                    <img src="{{ asset('uploads/' . $news->image) }}" class="w-100"
                                                        style="object-position: center center; object-fit: cover;"
                                                        alt="{{ $news->title }}">
                                                </div>
                                                <div class="fs-8 text-start">
                                                    <p class="mt-2 email">{{ $news->title }}</p>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="d-flex justify-content-center mt-2">
                                    <a href="{{ route('berita') }}"
                                        class="other-news d-flex align-items-center text-white text-decoration-none">
                                        <span class="fs-9">Lihat Berita Lainnya</span>
                                        <i class="bx bx-right-arrow-alt fs-8"></i>
                                    </a>
                                </div>
                            </div>
                        </section>
                    @endif
                </div>
            </div>

            <div class="footer row pb-5">
                <div class="col mb-5 mb-md-0">
                    <h4 class="text-start fs-3 mb-4">Navigasi Halaman</h4>
                    <div class="accordion accordion-flush p-0" id="accordionFlushExample">
                        @foreach ($menuItems['header'] as $menu)
                            @if (isset($menu['dropdown']))
                                <div class="accordion-item bg-transparent border-bottom">
                                    <h2 class="accordion-header p-0">
                                        <button
                                            class="accordion-button collapsed bg-transparent text-white p-0 lh-lg fs-7"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#flush-collapse{{ $loop->index }}" aria-expanded="false"
                                            aria-controls="flush-collapse{{ $loop->index }}">
                                            {{ $menu['name'] }}
                                        </button>
                                    </h2>
                                    <div id="flush-collapse{{ $loop->index }}" class="accordion-collapse collapse"
                                        aria-labelledby="flush-heading{{ $loop->index }}"
                                        data-bs-parent="#accordionFlushExample">
                                        @foreach ($menu['dropdown'] as $dropdown)
                                            <a href="{{ $dropdown['route'] }}"
                                                class="accordion-body d-flex text-decoration-none text-white pilates">{{ $dropdown['name'] }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="accordion-item bg-transparent border-bottom">
                                    <h2 class="accordion-header p-0">
                                        <a href="{{ $menu['route'] }}"
                                            class="accordion-button text-decoration-none fw-normal collapsed bg-transparent text-white p-0 lh-lg fs-7">
                                            {{ $menu['name'] }}
                                        </a>
                                    </h2>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="col text-end fs-8">
                    <h4 class="fs-3 mb-4 pb-2">Kontak Kami</h4>
                    <div class="d-flex flex-column">
                        <a href="https://maps.app.goo.gl/SBcNQFzbxBuyMTE8A" class="email text-white"
                            target="_blank">Jl. Poros Samarinda – Bontang, Kel. Sungai Siring, Samarinda – Kalimantan
                            Timur 75119</a>
                        <a href="https://wa.me/62811551944" target="_blank" class="email text-white">+62 811 551
                            944</a>
                        <a href="mailto:mail.aptpranotoairport@gmail.com" target="_blank"
                            class="email text-white">aptpranotoairport@gmail.com</a>
                        <div class="fs-4 d-flex gap-3 justify-content-end mt-5">
                            <a href="https://wa.me/62811551944" target="_blank" class="text-white social-media">
                                <i class="bx bxl-whatsapp"></i>
                            </a>
                            <a href="https://www.facebook.com/aptpranotoairport/" target="_blank"
                                class="text-white social-media">
                                <i class="bx bxl-facebook"></i>
                            </a>
                            <a href="https://www.twitter.com/aptp_airport" target="_blank"
                                class="text-white social-media">
                                <i class="bx bxl-twitter"></i>
                            </a>
                            <a href="https://www.youtube.com/@aptpranotoairport" target="_blank"
                                class="text-white social-media">
                                <i class="bx bxl-youtube"></i>
                            </a>
                            <a href="https://www.instagram.com/aptpranotoairport/" target="_blank"
                                class="text-white social-media">
                                <i class="bx bxl-instagram "></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer text-start fs-9 mt-5 d-flex justify-content-between align-items-center"
                style="background-color: #0b0b0b;">
                <span>Kantor Unit Penyelenggara Bandar Udara Kelas I A.P.T Pranoto Samarinda</span>
            </div>
        </footer>


    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.1/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.1/ScrollTrigger.min.js"></script>
    <script src="{{ asset('frontend/script.js') }}"></script>
    @stack('scripts')
    <script>
        // window.addEventListener('scroll', function() {
        //     const navbar = document.getElementById('navbar');
        //     if (window.scrollY > 10) {
        //         navbar.classList.add('scrolled');
        //     } else {
        //         navbar.classList.remove('scrolled');
        //     }
        // });
    </script>
</body>

</html>
