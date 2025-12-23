@extends('layouts.app')

@section('title', 'Sistem Informasi Akademik - SALUT Insan Cendekia')

@push('styles')
<style>
    .card {
        border-radius: 3rem !important; /* setara rounded-4 */
    }
</style>
@endpush

@section('content')

<!-- start banner Area -->
<section class="banner-area relative about-banner" id="home">
    <div class="overlay overlay-bg"></div>
    <div class="container">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="about-content col-lg-12">
                <h1 class="text-white">
                    Sistem Informasi Akademik
                </h1>
                <h3 class="fw-bold text-primary mb-3">SALUT INSAN CENDEKIA</h3>
            </div>
        </div>
    </div>
</section>
<!-- End banner Area -->

<div class="container my-5">
    <div class="card shadow p-4 mx-auto" style="max-width: 900px;">
        <h4 class="card-title text-center">Sistem Informasi Akademik</h4>
        <h5 class="card-text text-center">SALUT INSAN CENDEKIA</h5><br>
        <p class="card-text text-center text-primary">Pendaftaran Mahasiswa</p>

        <!-- WRAPPER AGAR KOTAK KECIL SEJAJAR -->
        <div class="d-flex gap-3 justify-content-center">

            <div class="card mx-3" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Vokasi/Sarjana</h5>
                    <p class="card-text">D3/D4/S1</p>
                    <a href="{{ url('/sarjana') }}" class="btn btn-outline-primary">Selengkapnya</a>
                </div>
            </div>

            <div class="card mx-3" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Magister</h5>
                    <p class="card-text">S2</p>
                    <a href="{{ url('/magister') }}" class="btn btn-outline-primary">Selengkapnya</a>
                </div>
            </div>

            <div class="card mx-3" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Doktoral</h5>
                    <p class="card-text">S3</p>
                    <a href="{{ url('/doktoral') }}" class="btn btn-outline-primary">Selengkapnya</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
