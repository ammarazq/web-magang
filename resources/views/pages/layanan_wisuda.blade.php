@extends('layouts.app')

@section('title', 'Layanan Wisuda - SALUT Insan Cendekia')

@section('content')
<!-- start banner Area -->
<section class="banner-area relative about-banner" id="home">   
    <div class="overlay overlay-bg"></div>
    <div class="container">             
        <div class="row d-flex align-items-center justify-content-center">
            <div class="about-content col-lg-12">
                <h1 class="text-white">
                    Layanan Wisuda      
                </h1>   
                <p class="text-white link-nav"><a href="{{ url('/') }}">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="{{ route('layanan_wisuda') }}">Layanan Wisuda</a></p>
            </div>  
        </div>
    </div>
</section>
<!-- End banner Area -->    

<!-- Start course-details Area -->
<section class="course-details-area pt-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 left-contents">
                <div class="main-image">
                    <img class="img-fluid" src="{{ asset('img/m-img.jpg') }}" alt="">
                </div>
                <div class="jq-tab-wrapper" id="horizontalTab">
                    <div class="jq-tab-menu">
                        <div class="jq-tab-title active" data-tab="1">Pendaftaran Wisuda</div>
                        <div class="jq-tab-title" data-tab="2">Permohonan Legalisir Ijazah dan Transkrip Nilai Online</div>
                    </div>
                    <div class="jq-tab-content-wrapper">
                        <div class="jq-tab-content active" data-tab="1">
                            Layanan Perkuliahan Mahasiswa Universitas Terbuka di SALUT Insan Cendekia adalah sebagai berikut:
                            <br>
                            <br>
                            1. Pendaftaran Wisuda<br>
                            2. Permohonan Legalisir Ijazah dan Transkrip Nilai Online<br>
                            <br>
                            <br>
                            Syarat untuk akses layanan diatas harus memenuhi:<br>
                            1. Mahasiswa tergabung kedalam kepesertaan SALUT Insan Cendekia<br>
                            2. Mahasiswa memiliki NPMSIC (Nomor Pokok Mahasiswa SALUT Insan Cendekia)<br>
                            3. Bebas dari semua tagihan perkuliahan/registrasi baik di UT maupun SALUT<br><br><br>
                            <ul class="course-list">
                                <li class="justify-content-between d-flex">
                                    <p>Silahkan klik button berikut untuk informasi mengenai Pendaftaran Wisuda</p>
                                    <a class="primary-btn text-uppercase" href="">Unduh</a>
                                </li>
                            </ul>
                        </div>
                        <div class="jq-tab-content" data-tab="2">
                            Layanan Perkuliahan Mahasiswa Universitas Terbuka di SALUT Insan Cendekia adalah sebagai berikut:
                            <br>
                            <br>
                            1. Pendaftaran Wisuda<br>
                            2. Permohonan Legalisir Ijazah dan Transkrip Nilai Online<br>
                            <br>
                            <br>
                            Syarat untuk akses layanan diatas harus memenuhi:<br>
                            1. Mahasiswa tergabung kedalam kepesertaan SALUT Insan Cendekia<br>
                            2. Mahasiswa memiliki NPMSIC (Nomor Pokok Mahasiswa SALUT Insan Cendekia)<br>
                            3. Bebas dari semua tagihan perkuliahan/registrasi baik di UT maupun SALUT<br><br><br>
                            <ul class="course-list">
                                <li class="justify-content-between d-flex">
                                    <p>Silahkan klik button berikut untuk informasai mengenai Permohonan Legalisir Ijazah dan Transkrip Nilai Online</p>
                                    <a class="primary-btn text-uppercase" href="">Unduh</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
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
<!-- End course-details Area -->

<!-- Start cta-two Area -->
<section class="cta-two-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 cta-left">
                <h1>Tanya Seputar UT?</h1>
            </div>
            <div class="col-lg-4 cta-right">
                <a class="primary-btn wh" href="#">Hello SIC</a>
            </div>
        </div>
    </div>  
</section> 
<!-- End cta-two Area -->   
@endsection