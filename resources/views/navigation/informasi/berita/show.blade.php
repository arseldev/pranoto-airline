@extends('layouts.laravel-default')

@section('title', $news->title . ' | APT PRANOTO')

@section('content')
<div class="container my-4 mb-5 pb-5">
  <div class="row">
    <div class="col-md-9 border-end">
      <h1 class="fw-bold mb-3">
        {{ $news->title }}
      </h1>
      <div class="mb-4 text-secondary">
        <small>
          {{ \Carbon\Carbon::parse($news->created_at)->format('d F Y') }} | {{ \Carbon\Carbon::parse($news->created_at)->format('H.i') }} WIB
        </small>
      </div>
    
      @if ($news->image)
        <img 
          src="{{ asset('uploads/' . $news->image) }}" 
          class="img-fluid mb-4 w-100" 
          style="object-fit: cover; object-position: center; border-radius: 6px;"
          alt="{{ $news->title }}">
      @endif
    </div>
    <div class="col-md-3"></div>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-9 border-end">
      <article class="fs-5 lh-lg">
        @foreach (explode("\n", $news->content) as $para)
          <p class="mb-4">{{ $para }}</p>
        @endforeach
      </article>
    </div>

    <div class="col-md-3">
      <h5 class="fw-bold mb-3">Berita Terbaru</h5>
      <div class="list-group list-group-flush">
        @foreach ($latestArticles as $article)
          <a href="{{ route('showNews', $article->slug) }}" class="list-group-item list-group-item-action p-2">
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
                <small>{{ $article->title }}</small>
              </div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </div>
</div>

@endsection