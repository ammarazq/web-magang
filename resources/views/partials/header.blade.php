<header id="header" id="home">
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-sm-6 col-8 header-top-left no-padding">
                    <ul>
                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
                        <li><a href="#"><i class="fa fa-behance"></i></a></li>
                    </ul>            
                </div>
                <div class="col-lg-6 col-sm-6 col-4 header-top-right no-padding">
                    <a href="tel:+9530123654896"><span class="lnr lnr-phone-handset"></span> <span class="text">+953 012 3654 896</span></a>
                    <a href="mailto:support@colorlib.com"><span class="lnr lnr-envelope"></span> <span class="text">support@colorlib.com</span></a>            
                </div>
            </div>                                  
        </div>
    </div>
    <div class="container main-menu">
        <div class="row align-items-center justify-content-between d-flex">
            <div id="logo">
                <a href="{{ url('/') }}"><img src="{{ asset('img/logo.png') }}" alt="SALUT Logo" /></a>
            </div>
            <nav id="nav-menu-container">
                <ul class="nav-menu">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/about') }}">About</a></li>
                    <li class="menu-has-children"><a href="#">Perkuliahan</a>
                        <ul>
                            <li><a href="{{ url('/jadwal_TTM') }}">Jadwal TTM/TUWEB</a></li>
                            <li><a href="{{ url('/Jadwal_Tuton') }}">Jadwal Tuton</a></li>
                            <li><a href="{{ url('/Jadwal_Ujian') }}">Jadwal Ujian</a></li>
                        </ul>
                    </li>
                    <li class="menu-has-children"><a href="#">Layanan</a>
                        <ul>
                            <li><a href="{{ url('/layanan_daftar') }}">Layanan Pendaftaran</a></li>
                            <li><a href="{{ url('/layanan_regis') }}">Layanan Registrasi</a></li>
                            <li><a href="{{ url('/layanan_TTM') }}">Layanan TTM</a></li>
                            <li><a href="{{ url('/layanan_ujian') }}">Layanan Ujian</a></li>
                            <li><a href="{{ url('/layanan_TAPS') }}">Layanaan TAPS</a></li>
                            <li><a href="{{ url('/layanan_wisuda') }}">Layanan Wisuda</a></li>
                        </ul>
                    </li>
                    <li class="menu-has-children"><a href="#">Fakultas</a>
                        <ul>
                            <li><a href="{{ url('/FEB') }}">Fakultas Ekonomi dan Bisnis</a></li>
                            <li><a href="{{ url('/FKIP') }}">Fakultas Keguruan dan Ilmu Pendidikan</a></li>
                            <li><a href="{{ url('/FISIP') }}">Fakultas Hukum, Ilmu Sosial, dan Ilmu Politik</a></li>
                            <li><a href="{{ url('/FAST') }}">Fakultas Sains dan Teknologi</a></li>
                            <li><a href="{{ url('/Pascasarjana') }}">Sekolah PascaSarjana (SPs)</a></li>
                        </ul>
                    </li>
                    <li class="menu-has-children"><a href="#">Aplikasi</a>
                        <ul>
                            <li><a href="{{ url('/Tutorial_Online') }}">Tutorial Online</a></li>
                            <li><a href="{{ url('/Tutorial_Webinar') }}">Tutorial Webinar</a></li>
                            <li><a href="{{ url('/Praktikum') }}">Praktikum</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ url('/gallery') }}">Gallery</a></li>
                    <li><a href="{{ url('/contact') }}">Contact</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>
