@extends('layouts.laravel-default')

@section('title', 'SOP PPID | APT PRANOTO')

@section('content')
    <div class="content-wrapper">
        <div class="container pb-3">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Ups! Ada kesalahan:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <h2 class="mb-4 fw-bold fs-1 pt-md-5">Tata Cara Pengajuan Informasi Publik</h2>

            <h4 class="fs-4">Persyaratan Dokumen Pengajuan Informasi Publik</h4>
            <ul>
                <li>Scan KTP</li>
                <li>Surat Pernyataan Pertanggung Jawaban Informasi Publik</li>
                <li><a href="#formPengajuan" class="link link-dark">Isi Form Dibawah</a></li>
            </ul>
            <a href="https://docs.google.com/document/d/1hdV1e_SkNHG5KNDiYxGXsX125EaGPZwN/edit?usp=sharing&ouid=116067769203631007023&rtpof=true&sd=true"
                class="btn btn-primary mb-5" target="_blank">Unduh Surat Pernyataan Pertanggung Jawaban</a>

            <!-- Slider Gambar -->
            <div id="caraSlider" class="carousel slide mb-5 mx-md-5 px-md-5" data-bs-ride="carousel"
                data-bs-interval="10000">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#caraSlider" data-bs-slide-to="0" class="active"
                        aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#caraSlider" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#caraSlider" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="https://aptpairport.id/wp-content/uploads/2025/01/tata-cara-permohonan-informasi-publik_001.png"
                            class="d-block w-100" alt="Gambar 1">
                    </div>
                    <div class="carousel-item">
                        <img src="https://aptpairport.id/wp-content/uploads/2025/01/tata-cara-permohonan-informasi-publik_002.png"
                            class="d-block w-100" alt="Gambar 2">
                    </div>
                    <div class="carousel-item">
                        <img src="https://aptpairport.id/wp-content/uploads/2025/01/tata-cara-permohonan-informasi-publik_003.png"
                            class="d-block w-100" alt="Gambar 2">
                    </div>
                    <!-- Tambahkan item sesuai kebutuhan -->
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#caraSlider" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#caraSlider" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
            <br id="formPengajuan">
            <br>
            <form action="{{ route('storePengajuanInformasiPublik') }}" method="POST" enctype="multipart/form-data"
                class="py-5">
                @csrf

                {{-- STEP 1 --}}
                <div class="card mb-4" id="formStep1">
                    <div class="card-header">Form Pengajuan Informasi Publik - Tahap 1</div>
                    <div class="card-body">
                        @foreach ([
            'ktp' => 'Upload Scan KTP',
            'surat_pertanggungjawaban' => 'Upload Surat Pernyataan Pertanggung Jawaban',
        ] as $name => $label)
                            <div class="mb-3">
                                <label for="{{ $name }}" class="form-label">{{ $label }} <span
                                        class="text-danger">*</span></label>
                                <input type="file" name="{{ $name }}"
                                    class="form-control @error($name) is-invalid @enderror" id="{{ $name }}">
                                @error($name)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach

                        <div class="mb-3">
                            <label for="surat_permintaan" class="form-label">Surat Permintaan Informasi dari (Instansi /
                                Organisasi) <span class="text-danger">*</span></label>
                            <input type="text" name="surat_permintaan" id="surat_permintaan"
                                class="form-control @error('surat_permintaan') is-invalid @enderror"
                                value="{{ old('surat_permintaan') }}">
                            @error('surat_permintaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" onclick="nextStep()">Lanjut</button>
                        </div>
                    </div>
                </div>

                {{-- STEP 2 --}}
                <div class="card" id="formStep2" style="display: none;">
                    <div class="card-header">Form Pengajuan Informasi Publik - Tahap 2</div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ([
            'nama' => 'Nama',
            'alamat' => 'Alamat',
            'pekerjaan' => 'Pekerjaan',
            'npwp' => 'NPWP',
            'no_hp' => 'No HP / WA',
            'email' => 'Email',
        ] as $name => $label)
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">{{ $label }} <span
                                            class="text-danger">*</span></label>
                                    <input type="{{ $name === 'email' ? 'email' : 'text' }}" name="{{ $name }}"
                                        class="form-control @error($name) is-invalid @enderror"
                                        value="{{ old($name) }}">
                                    @error($name)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                        @foreach ([
            'rincian_informasi' => 'Rincian Informasi yang Dibutuhkan',
            'tujuan_informasi' => 'Tujuan Penggunaan Informasi',
        ] as $name => $label)
                            <div class="mb-3">
                                <label class="form-label">{{ $label }} <span class="text-danger">*</span></label>
                                <textarea name="{{ $name }}" class="form-control @error($name) is-invalid @enderror" rows="3">{{ old($name) }}</textarea>
                                @error($name)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach

                        <div class="mb-3">
                            <label for="caraPeroleh" class="form-label">Cara Memperoleh Informasi <span
                                    class="text-danger">*</span></label>
                            <select id="caraPeroleh" name="cara_memperoleh"
                                class="form-select @error('cara_memperoleh') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih salah satu</option>
                                <option value="Melihat" {{ old('cara_memperoleh') == 'Melihat' ? 'selected' : '' }}>
                                    Melihat /
                                    Membaca / Mendengarkan / Mencatat</option>
                                <option value="Salinan" {{ old('cara_memperoleh') == 'Salinan' ? 'selected' : '' }}>
                                    Mendapatkan copy salinan (hard copy)</option>
                            </select>
                            @error('cara_memperoleh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="caraSalinan" class="form-label">Cara Mendapat Salinan Informasi <span
                                    class="text-danger">*</span></label>
                            <select id="caraSalinan" name="cara_salinan"
                                class="form-select @error('cara_salinan') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih salah satu</option>
                                @foreach (['Langsung', 'Kurir', 'Pos', 'Fax', 'Email', 'WhatsApp'] as $opsi)
                                    <option value="{{ $opsi }}"
                                        {{ old('cara_salinan') == $opsi ? 'selected' : '' }}>
                                        {{ $opsi }}</option>
                                @endforeach
                            </select>
                            @error('cara_salinan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">Kembali Ke Tahap
                                1</button>
                            <button type="submit" class="btn btn-success">Kirim Pengajuan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function nextStep() {
            document.getElementById('formStep1').style.display = 'none';
            document.getElementById('formStep2').style.display = 'block';
        }

        function prevStep() {
            document.getElementById('formStep1').style.display = 'block';
            document.getElementById('formStep2').style.display = 'none';
        }
    </script>
@endsection
