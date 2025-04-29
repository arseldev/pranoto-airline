@extends('layouts.master-without-nav')

@section('title')
  @lang('translation.Login')
@endsection

@section('body')

  <body>
  @endsection

  @section('content')
  <div class="account-pages vh-100 d-flex align-items-center">
    <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card overflow-hidden">
              <div class="bg-primary bg-soft">
                <div class="row">
                  <div class="col-8">
                    <div class="text-primary p-4">
                      <h5 class="text-primary">Selamat Datang !</h5>
                      <p>Masuk untuk Melanjutkan ke Dasbor Bandara APT Pranoto.</p>
                    </div>
                  </div>
                  <div class="col-4 align-self-end mb-3">
                    <img src="{{ URL::asset('/assets/images/logo-blu.png') }}" alt="" class="img-fluid">
                  </div>
                </div>
              </div>
              <div class="card-body pt-0">
                <div class="auth-logo">
                  <a href="index" class="auth-logo-light">
                    <div class="avatar-md profile-user-wid mb-4">
                      <span class="avatar-title rounded-circle bg-light">
                        <img src="{{ URL::asset('/assets/images/logo-light.svg') }}" alt="" class="rounded-circle" height="34">
                      </span>
                    </div>
                  </a>

                  <div class="auth-logo-dark">
                    <div>
                      <div>
                        <div class="mb-4">
                          <!-- <span class="avatar-title rounded-circle bg-light">
                            <img src="{{ URL::asset('/assets/images/air-plane-icon.jpg') }}" alt="" class="rounded-circle" height="90">
                          </span> -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="">
                  <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                    @if (session('unverified'))
                      <div class="alert alert-warning">
                        {{ session('unverified') }}
                      </div>
                    @endif
                    @error('unverified')
                      <div class="alert alert-warning">
                          {{ $message }}
                      </div>
                    @enderror
                    @error('credentials')
                      <div class="alert alert-danger">
                          {{ $message }}
                      </div>
                    @enderror
                      <label for="email" class="form-label">Email</label>
                      <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Enter Email" autocomplete="email" autofocus>
                      @error('email')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>

                    <div class="mb-3">
                      <div class="float-end">
                        <!-- @if (Route::has('password.request'))
                          <a href="{{ route('password.request') }}" class="text-muted">Forgot password?</a>
                        @endif -->
                      </div>
                      <label class="form-label">Kata Sandi</label>
                      <div class="input-group auth-pass-inputgroup @error('password') is-invalid @enderror">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="userpassword" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon">
                        <button class="btn btn-light" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                        @error('password')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                    </div>

                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="remember" {{ old('rememberd') ? 'checked' : '' }}>
                      <label class="form-check-label" for="remember">
                        Ingat saya
                      </label>
                    </div>

                    <div class="d-grid mt-3">
                      <button class="btn btn-primary waves-effect waves-light" type="submit">Masuk</button>
                    </div>
                  </form>
                </div>

              </div>
            </div>
            <div class="mt-3 text-center">
              <div>
                <p>
                  Belum punya akun ? <a href="{{ route('register') }}" class="fw-medium text-primary">Daftar sekarang! </a>
                </p>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
    <!-- end account-pages -->
  @endsection
