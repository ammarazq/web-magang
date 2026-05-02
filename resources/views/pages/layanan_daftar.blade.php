@extends('layouts.app')

@section('title', 'Layanan Pendaftaran - SALUT Insan Cendekia')

@section('content')
<!-- start banner Area -->
<section class="banner-area relative about-banner" id="home">   
    <div class="overlay overlay-bg"></div>
    <div class="container">             
        <div class="row d-flex align-items-center justify-content-center">
            <div class="about-content col-lg-12">
                <h1 class="text-white">
                    Layanan Pendaftaran        
                </h1>   
                <p class="text-white link-nav"><a href="{{ url('/') }}">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="{{ route('layanan_daftar') }}"> Layanan Pendaftaran</a></p>
            </div>  
        </div>
    </div>
</section>
<!-- End banner Area -->    

<!-- Start course-details Area -->
<section class="course-details-area pt-120 pb-120" style="background-color: #fafbfc;">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-12">
                <h2 style="font-weight: 700; color: #222; margin-bottom: 20px;">Layanan Pendaftaran</h2>
                <p style="color: #777; font-size: 16px; line-height: 1.8;">
                    Layanan Pendaftaran SALUT Insan Cendekia merupakan kumpulan berkas pendaftaran yang dibutuhkan untuk Mendaftar menjadi Mahasiswa Universitas Terbuka
                </p>
                <hr style="border-top: 1px dashed #eee; margin-top: 40px;">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="registration-table-wrap" style="background: white; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 40px;">
                    <h3 class="mb-4" style="font-weight: 700; color: #222; font-size: 28px;">Berkas Pendaftaran</h3>
                    <div class="table-responsive">
                        <table class="table" style="border-collapse: separate; border-spacing: 0; min-width: 800px; margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <th style="border-bottom: 2px solid #f4f6f8; border-top: none; color: #111; font-weight: 600; padding: 20px; font-size: 15px;">NO</th>
                                    <th style="border-bottom: 2px solid #f4f6f8; border-top: none; color: #111; font-weight: 600; padding: 20px; font-size: 15px;">BERKAS</th>
                                    <th style="border-bottom: 2px solid #f4f6f8; border-top: none; color: #111; font-weight: 600; padding: 20px; font-size: 15px;">SYARAT KHUSUS</th>
                                    <th style="border-bottom: 2px solid #f4f6f8; border-top: none; color: #111; font-weight: 600; padding: 20px; font-size: 15px;">LINK AKSES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">1</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">Formulir Pendaftaran NON-RPL/Reguler</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">Non-RPL/Reguler</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; padding: 25px 20px; vertical-align: middle;">
                                        <a href="https://drive.google.com/file/d/1xBX-laojWEjBn5vJGYrX7P4DgTBmHeAS/view" class="btn" style="background-color: #409df5; color: white; padding: 10px 30px; border-radius: 4px; font-weight: 500; font-size: 14px; border: none; box-shadow: none;">Unduh</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">2</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">Formulir Pendaftaran RPL/Transfer</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">RPL (Rekognisi Pembelajaran Lampau)</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; padding: 25px 20px; vertical-align: middle;">
                                        <a href="#" class="btn" style="background-color: #409df5; color: white; padding: 10px 30px; border-radius: 4px; font-weight: 500; font-size: 14px; border: none; box-shadow: none;">Unduh</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">3</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">Formulir Keabsahan dan Kebenaran Dokumen</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">-</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; padding: 25px 20px; vertical-align: middle;">
                                        <a href="#" class="btn" style="background-color: #409df5; color: white; padding: 10px 30px; border-radius: 4px; font-weight: 500; font-size: 14px; border: none; box-shadow: none;">Unduh</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">4</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">Surat Keterangan Mampu Membaca Al-Qur'an</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">Pendaftar Prodi<br>S1-Pendidikan Agama Islam</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; padding: 25px 20px; vertical-align: middle;">
                                        <a href="#" class="btn" style="background-color: #409df5; color: white; padding: 10px 30px; border-radius: 4px; font-weight: 500; font-size: 14px; border: none; box-shadow: none;">Unduh</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: none; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">5</td>
                                    <td style="border-bottom: none; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">Daftar Riwayat Hidup</td>
                                    <td style="border-bottom: none; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">RPL (Rekognisi Pembelajaran Lampau)</td>
                                    <td style="border-bottom: none; border-top: none; padding: 25px 20px; vertical-align: middle;">
                                        <a href="#" class="btn" style="background-color: #409df5; color: white; padding: 10px 30px; border-radius: 4px; font-weight: 500; font-size: 14px; border: none; box-shadow: none;">Unduh</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-5">
                    <h3 style="font-weight: 700; color: #222; margin-bottom: 20px;">Catatan Penting:</h3>
                    <div style="background: white; border-left: 3px solid #ff7b00; padding: 25px 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                        <p style="color: #ff7b00; margin: 0; font-size: 16px;">-</p>
                    </div>
                </div>
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
