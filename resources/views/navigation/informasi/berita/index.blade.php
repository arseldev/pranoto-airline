@extends('layouts.laravel-default')

@section('title', 'Berita | APT PRANOTO')

@section('content')
<section class="">
  <div class="container">
    <div class="row">
      <!-- Kolom Kiri: Berita Utama -->
      <div class="col-md-8 border-end">
        @if ($headline)
          <a href="{{route('showNews', $headline->slug)}}" class="text-decoration-none text-dark">
            <img 
              src="{{ asset('uploads/' . $headline->image) }}" 
              class="img-fluid mb-2 w-100 pilates" 
              alt="{{ $headline->title }}"
              style="max-height: 400px; object-fit: cover; object-position: center center; border-radius: 6px;"
            >
            <h2 class="fw-bold email">
              @if ($headline->is_headline)
                <span class="badge bg-danger me-2">HOT</span>
              @endif
              <span class="text-dark">{{ $headline->title }}</span>
            </h2>
          </a>

          <div class="row row-cols-1 row-cols-md-3 g-2 mt-2">
            @foreach ($subHeadlines as $sub)
              <a href="{{route('showNews', $sub->slug)}}" class="col text-decoration-none">
                <div class="border-top pt-2 text-dark email">
                  @if ($sub->is_headline)
                    <span class="badge bg-danger me-1">HOT</span>
                  @endif
                  <small class="fs-7">{{ $sub->title }}</small>
                </div>
              </a>
            @endforeach
          </div>
        @endif
      </div>

      <!-- Kolom Kanan: Artikel Terbaru -->
      <div class="col-md-4">
        <h5 class="fw-bold mb-3">Berita Terbaru</h5>
        <div class="list-group list-group-flush">
          @foreach ($latestArticles as $article)
            <a href="{{route('showNews', $article->slug)}}" class="list-group-item list-group-item-action p-2">
              <div class="row g-2 align-items-center">
                <div class="col-4">
                  <div class="ratio ratio-4x3 overflow-hidden">
                    <img 
                      src="{{ asset('uploads/' . $article->image) }}" 
                      alt="{{ $article->title }}" 
                      class="w-100 h-100" 
                      style="object-fit: cover; object-position: center center;"
                    >
                  </div>
                </div>
                <div class="col-8">
                  <small class="d-block">{{ $article->title }}</small>
                </div>
              </div>
            </a>
          @endforeach
        </div>
      </div>

    </div>
  </div>
</section>
<section>
  <h3 class="fs-3 mb-3">Berita Lainnya</h3>
  @foreach ($otherArticles as $article)
    <a href="{{route('showNews', $article->slug)}}" class="d-flex border-start-0 border-end-0 text-dark text-decoration-none list-group-item list-group-item-action">
      <img 
        src="{{ asset('uploads/' . $article->image) }}" 
        alt="{{ $article->title }}" 
        class="flex-shrink-0 me-3" 
        style="width: 80px; height: 80px; object-fit: cover;"
      >
      <div>
        @if ($article->is_headline)
          <span class="badge bg-danger me-1">HOT</span>
        @endif
        {{ $article->title }}
      </div>
    </a>
  @endforeach
</section>

@endsection
