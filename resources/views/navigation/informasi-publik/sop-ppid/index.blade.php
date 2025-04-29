@extends('layouts.laravel-default')

@section('title', 'SOP PPID | APT PRANOTO')

@push('styles')
    <style>
        .frame-document {
            width: 60%;
        }

        @media only screen and (max-width: 750px) {
            .frame-document {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <section class="container pb-4">
        <h1 class="mb-4 fw-bold fs-1">SOP PPID</h1>

        <div class="mb-4">
            <p>
                Pada halaman ini, Anda dapat melihat SOP terkait dengan pengelolaan dan layanan Informasi Publik di PPID
                (Pejabat Pengelola Informasi dan Dokumentasi). SOP ini berfungsi sebagai panduan dalam memberikan layanan
                informasi yang transparan, tepat waktu, dan sesuai dengan ketentuan yang berlaku.
            </p>
        </div>
        <div class="d-flex justify-content-center">
            <div class="vh-100 w-100 w-md-75 d-flex justify-content-center p-3 p-md-5 bg-dark">
                <iframe src="https://drive.google.com/file/d/1GzeFVwkDCu9KAQ3JrCvjNYfDRAbYk5P7/preview" class="frame-document"
                    height="100%"></iframe>
            </div>
        </div>
    </section>
@endsection
