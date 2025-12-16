@extends('layouts.app')

@section('title', 'Education')

@section('content')
    <section class="banner-area relative" id="home">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row fullscreen d-flex align-items-center justify-content-between">
                <div class="banner-content col-lg-9 col-md-12">
                    <h1 class="text-uppercase">
                        salut insan cendekia <b>BREBES</b>
                    </h1>
                    <h2 style="color: white">Universitas Terbuka Purwokerto</h2>
                    <p class="pt-10 pb-10">
                        UT memiliki unit layanan yang disebut SALUT (Sentra Layanan UT) yang tersebar di daerah, 
                        salah satunya adalah SALUT Insan Cendekia. SALUT berfungsi sebagai titik kontak bagi mahasiswa, tutor, 
                        dan pemangku kepentingan lainnya untuk memperoleh informasi, konsultasi, dan berbagai layanan lain.
                    </p>
                    <a href="#" class="primary-btn text-uppercase">Daftar</a>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner Area -->

    <!-- Start feature Area -->
    <section class="feature-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="single-feature">
                        <div class="title">
                            <h4>Pendaftaran Mahasiswa Baru</h4>
                        </div>
                        <div class="desc-wrap">
                            <p> S1 Reg : 25 Agustus 2025 - 27 Januari 2026</p>
                            <p>S1 RPL : 25 Agustus 2025 - 1 Desember 2025</p>
                            <p>S2 & S3 : 13 Agustus 2025 - 15 Oktober 2025</p>
                            <a href="#">Daftar</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single-feature">
                        <div class="title">
                            <h4>Jadwal Perkuliahan</h4>
                        </div>
                        <div class="desc-wrap"><br>
                            <p>
                                Jadwal Perkuliahan dan Ujian Semester 2025/2026 Genap
                            </p><br>
                            <a href="#">Lihat</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single-feature">
                        <div class="title">
                            <h4>Layanan SALUT</h4>
                        </div>
                        <div class="desc-wrap"><br>
                            <p>
                                Layanan SALUT hanya bisa diakses oleh mahasiswa SALUT Insan Cendekia
                            </p><br>
                            <a href="#">Lihat</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End feature Area -->

    <!-- Start popular-course Area -->
    <section class="popular-course-area section-gap">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-8">
                    <div class="title text-center">
                        <h1 class="mb-10">Fasilitas dan Kegiatan Mahasiswa</h1>
                        <p>There is a moment in the life of any aspiring.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="active-popular-carusel">
                    <!-- Repeat for each facility -->
                    <div class="single-popular-carusel">
                        <div class="thumb-wrap relative">
                            <div class="thumb relative">
                                <div class="overlay overlay-bg"></div>
                                <img class="img-fluid" src="/img/p1.jpg" alt="">
                            </div>
                        </div>
                        <div class="details">
                            <a href="#">
                                <h4>Front Office</h4>
                            </a>
                            <p>SALUT Insan Cendekia siap melayani dengan ruangan front office yang nyaman dan fasilitas coffee break yang bisa kamu nikmati.</p>
                        </div>
                    </div>
                    <!-- ...repeat for other facilities... -->
                </div>
            </div>
        </div>
    </section>
    <!-- End popular-course Area -->

    <!-- Start search-course Area -->
    <section class="search-course-area relative">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-6 col-md-6 search-course-left">
                    <h1 class="text-white">
                        Kuliah Bergengsi <br>
                        ala Gen-Z di UT!
                    </h1>
                    <p>
                        Penjaminan Mutu Universitas Terbuka selalu dijaga sejak awal berdiri tahun 1984 sebagai Perguruan Tinggi Negeri Berbadan Hukum yang melakukan pembelajaran jarak jauh berbasis teknologi, Cloud, AI, DLL. Didukung dengan Dosen dan Tutor yang berkompeten di bidangnya dapat membimbing mahasiswa untuk siap menghadapi tantangan global.
                    </p>
                    <div class="row details-content">
                        <div class="col single-detials">
                            <span class="lnr lnr-graduation-hat"></span>
                            <a href="#"><h4>Dosen & Tutor</h4></a>
                            <p>
                                Dosen dan Tutor Universitas Terbuka berasal dari lulusan Perguruan Tinggi ternama baik di Dalam maupun Luar Negeri.
                            </p>
                        </div>
                        <div class="col single-detials">
                            <span class="lnr lnr-license"></span>
                            <a href="#"><h4>Penghargaan</h4></a>
                            <p>
                                Penghargaan demi penghargaan terus diraih Universitas Terbuka baik penghargaan Nasional maupun Internasional.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 search-course-right section-gap">
                    <form class="form-wrap" action="#">
                        <h4 class="text-white pb-20 text-center mb-30">FAQ</h4>
                        <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Your Name'" >
                        <input type="phone" class="form-control" name="phone" placeholder="Nomor WhatsApp" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Your Phone Number'" >
                        <input type="email" class="form-control" name="email" placeholder="Email Aktif" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Your Email Address'" >
                        <div class="form-select" id="service-select">
                            <select>
                                <option datd-display="">Pilih Layanan</option>
                                <option value="1">Pendaftaran</option>
                                <option value="2">Perkuliahan</option>
                                <option value="3">Biaya</option>
                                <option value="4">Kemahasiswaan</option>
                            </select>
                        </div>
                        <button class="primary-btn text-uppercase">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- End search-course Area -->

    <!-- Start upcoming-event Area -->
    <section class="upcoming-event-area section-gap">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-8">
                    <div class="title text-center">
                        <h1 class="mb-10">Upcoming Events SALUT Insan Cendekia</h1>
                        <p>Coming Soon</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="active-upcoming-event-carusel">
                    <!-- Repeat for each event -->
                    <div class="single-carusel row align-items-center">
                        <div class="col-12 col-md-6 thumb">
                            <img class="img-fluid" src="/img/e1.jpg" alt="">
                        </div>
                        <div class="detials col-12 col-md-6">
                            <p>25th February, 2018</p>
                            <a href="#"><h4>The Universe Through
                            A Child S Eyes</h4></a>
                            <p>
                                For most of us, the idea of astronomy is something we directly connect to “stargazing”, telescopes and seeing magnificent displays in the heavens.
                            </p>
                        </div>
                    </div>
                    <!-- ...repeat for other events... -->
                </div>
            </div>
        </div>
    </section>
    <!-- End upcoming-event Area -->

    <!-- Start review Area -->
    <section class="review-area section-gap relative">
        <div class="overlay overlay-bg"></div>
        <div class="container">
            <div class="row">
                <div class="active-review-carusel">
                    <!-- Repeat for each review -->
                    <div class="single-review item">
                        <div class="title justify-content-start d-flex">
                            <a href="#"><h4>Fannie Rowe</h4></a>
                            <div class="star">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                            </div>
                        </div>
                        <p>
                            Accessories Here you can find the best computer accessory for your laptop, monitor, printer, scanner, speaker. Here you can find the best computer accessory for your laptop, monitor, printer, scanner, speaker.
                        </p>
                    </div>
                    <!-- ...repeat for other reviews... -->
                </div>
            </div>
        </div>
    </section>
    <!-- End review Area -->

    <!-- Start cta-one Area -->
    <section class="cta-one-area relative section-gap">
        <div class="container">
            <div class="overlay overlay-bg"></div>
            <div class="row justify-content-center">
                <div class="wrap">
                    <h1 class="text-white">Become an instructor</h1>
                    <p>
                        There is a moment in the life of any aspiring astronomer that it is time to buy that first telescope. It’s exciting to think about setting up your own viewing station whether that is on the deck.
                    </p>
                    <a class="primary-btn wh" href="#">Apply for the post</a>
                </div>
            </div>
        </div>
    </section>
    <!-- End cta-one Area -->

    <!-- Start blog Area -->
    <section class="blog-area section-gap" id="blog">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="menu-content pb-70 col-lg-8">
                    <div class="title text-center">
                        <h1 class="mb-10">Latest posts from our Blog</h1>
                        <p>In the history of modern astronomy there is.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 single-blog">
                    <div class="thumb">
                        <img class="img-fluid" src="/img/b1.jpg" alt="">
                    </div>
                    <p class="meta">25 April, 2018  |  By <a href="#">Mark Wiens</a></p>
                    <a href="blog-single.html">
                        <h5>Addiction When Gambling Becomes A Problem</h5>
                    </a>
                    <p>
                        Computers have become ubiquitous in almost every facet of our lives. At work, desk jockeys spend hours in front of their.
                    </p>
                    <a href="#" class="details-btn d-flex justify-content-center align-items-center"><span class="details">Details</span><span class="lnr lnr-arrow-right"></span></a>
                </div>
                <div class="col-lg-3 col-md-6 single-blog">
					<div class="thumb">
						<img class="img-fluid" src="img/b2.jpg" alt="">								
					</div>
					<p class="meta">25 April, 2018  |  By <a href="#">Mark Wiens</a></p>
					<a href="blog-single.html">
						<h5>Computer Hardware Desktops And Notebooks</h5>
					</a>
					<p>
						Ah, the technical interview. Nothing like it. Not only does it cause anxiety, but it causes anxiety for several different reasons. 
					</p>
					<a href="#" class="details-btn d-flex justify-content-center align-items-center"><span class="details">Details</span><span class="lnr lnr-arrow-right"></span></a>						
				</div>
				<div class="col-lg-3 col-md-6 single-blog">
					<div class="thumb">
					    <img class="img-fluid" src="img/b3.jpg" alt="">								
					</div>
					<p class="meta">25 April, 2018  |  By <a href="#">Mark Wiens</a></p>
					<a href="blog-single.html">
						<h5>Make Myspace Your Best Designed Space</h5>
					</a>
					<p>
						Plantronics with its GN Netcom wireless headset creates the next generation of wireless headset and other products such as wireless.
					</p>
					<a href="#" class="details-btn d-flex justify-content-center align-items-center"><span class="details">Details</span><span class="lnr lnr-arrow-right"></span></a>									
				</div>
				<div class="col-lg-3 col-md-6 single-blog">
					<div class="thumb">
						<img class="img-fluid" src="img/b4.jpg" alt="">								
					</div>
					<p class="meta">25 April, 2018  |  By <a href="#">Mark Wiens</a></p>
					<a href="blog-single.html">
						<h5>Video Games Playing With Imagination</h5>
					</a>
					<p>
						About 64% of all on-line teens say that do things online that they wouldn’t want their parents to know about.   11% of all adult internet 
					</p>
					<a href="#" class="details-btn d-flex justify-content-center align-items-center"><span class="details">Details</span><span class="lnr lnr-arrow-right"></span></a>							
				</div>
            </div>
        </div>
    </section>
    <!-- End blog Area -->

    <!-- Start cta-two Area -->
    <section class="cta-two-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 cta-left">
                    <h1>Not Yet Satisfied with our Trend?</h1>
                </div>
                <div class="col-lg-4 cta-right">
                    <a class="primary-btn wh" href="#">view our blog</a>
                </div>
            </div>
        </div>
    </section>
    <!-- End cta-two Area -->
@endsection
