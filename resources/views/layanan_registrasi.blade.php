@extends('layouts.app')

@section('title', 'Layanan Registrasi')

@section('content')
<section class="banner-area relative about-banner" id="home">    
    <div class="overlay overlay-bg"></div>
    <div class="container">                
        <div class="row d-flex align-items-center justify-content-center">
            <div class="about-content col-lg-12">
                <h1 class="text-white">Layanan Registrasi</h1>    
                <p class="text-white link-nav">
                    <a href="{{ url('/') }}">Home </a>  
                    <span class="lnr lnr-arrow-right"></span>  
                    <a href="{{ url('/layanan_registrasi') }}"> Layanan Pendaftaran</a>
                </p>
            </div>  
        </div>
    </div>
</section>

<section class="course-details-area pt-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 left-contents">
                <!-- Konten tab bisa ditambahkan di sini -->
            </div>
            <div class="col-lg-4 right-contents">
                <ul>
                    <li>
                        <a class="justify-content-between d-flex" href="#">
                            <p>Koordinator Layanan</p> 
                            <span class="or">Maulana Yusuf Habibi, S. Kom</span>
                        </a>
                    </li>
                    <li>
                        <a class="justify-content-between d-flex" href="#">
                            <p>ID Pegawai </p>
                            <span>254194003</span>
                        </a>
                    </li>
                    <li>
                        <a class="justify-content-between d-flex" href="#">
                            <p>Email</p>
                            <span>myhabibi.sic.ut@gmail.com</span>
                        </a>
                    </li>
                </ul>
                <a href="#" class="primary-btn text-uppercase">Tanya Layanan</a>
            </div>
        </div>
    </div>  
</section>
@endsection
