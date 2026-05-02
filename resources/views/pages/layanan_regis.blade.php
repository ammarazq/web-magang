@extends('layouts.app')

@section('title', 'Layanan Registrasi - SALUT Insan Cendekia')

@section('content')
<!-- start banner Area -->
<section class="banner-area relative about-banner" id="home">   
    <div class="overlay overlay-bg"></div>
    <div class="container">             
        <div class="row d-flex align-items-center justify-content-center">
            <div class="about-content col-lg-12">
                <h1 class="text-white">
                    Layanan Registrasi        
                </h1>   
                <p class="text-white link-nav"><a href="{{ url('/') }}">Home </a>  <span class="lnr lnr-arrow-right"></span>  <a href="{{ route('layanan_regis') }}"> Layanan Registrasi</a></p>
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
                <h2 style="font-weight: 700; color: #222; margin-bottom: 20px;">Layanan Registrasi</h2>
                <p style="color: #777; font-size: 16px; line-height: 1.8;">
                    Layanan Registrasi SALUT Insan Cendekia merupakan fasilitas Mahasiswa SALUT Insan Cendekia untuk melakukan Registrasi Mata Kuliah
                </p>
                <hr style="border-top: 1px dashed #eee; margin-top: 40px;">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="registration-table-wrap" style="background: white; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 40px;">
                    <h3 class="mb-4" style="font-weight: 700; color: #222; font-size: 28px;">Jenis Layanan Registrasi</h3>
                    <div class="table-responsive">
                        <table class="table" style="border-collapse: separate; border-spacing: 0; min-width: 800px; margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <th style="border-bottom: 2px solid #f4f6f8; border-top: none; color: #111; font-weight: 600; padding: 20px; font-size: 15px;">NO</th>
                                    <th style="border-bottom: 2px solid #f4f6f8; border-top: none; color: #111; font-weight: 600; padding: 20px; font-size: 15px;">LAYANAN REGISTRASI</th>
                                    <th style="border-bottom: 2px solid #f4f6f8; border-top: none; color: #111; font-weight: 600; padding: 20px; font-size: 15px;">JADWAL LAYANAN</th>
                                    <th style="border-bottom: 2px solid #f4f6f8; border-top: none; color: #111; font-weight: 600; padding: 20px; font-size: 15px;">LINK AKSES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">1</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">Permohonan Registrasi</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">18 November 2025 - 7 Februari 2026</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; padding: 25px 20px; vertical-align: middle;">
                                        <a href="#" class="btn" style="background-color: #409df5; color: white; padding: 10px 30px; border-radius: 4px; font-weight: 500; font-size: 14px; border: none; box-shadow: none;">Lihat</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">2</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">Registrasi Mata Kuliah Pilihan</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">18 November 2025 - 7 Februari 2026</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; padding: 25px 20px; vertical-align: middle;">
                                        <a href="#" class="btn" style="background-color: #409df5; color: white; padding: 10px 30px; border-radius: 4px; font-weight: 500; font-size: 14px; border: none; box-shadow: none;">Lihat</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">3</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">Tambah/Ulang/Hapus Mata Kuliah</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">4 Januari - 7 Februari 2026</td>
                                    <td style="border-bottom: 1px solid #f4f6f8; border-top: none; padding: 25px 20px; vertical-align: middle;">
                                        <a href="#" class="btn" style="background-color: #409df5; color: white; padding: 10px 30px; border-radius: 4px; font-weight: 500; font-size: 14px; border: none; box-shadow: none;">Lihat</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: none; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">4</td>
                                    <td style="border-bottom: none; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">Permohonan Cuti Kuliah</td>
                                    <td style="border-bottom: none; border-top: none; color: #777; padding: 25px 20px; vertical-align: middle; font-size: 16px;">1 - 8 Februari 2026</td>
                                    <td style="border-bottom: none; border-top: none; padding: 25px 20px; vertical-align: middle;">
                                        <a href="#" class="btn" style="background-color: #409df5; color: white; padding: 10px 30px; border-radius: 4px; font-weight: 500; font-size: 14px; border: none; box-shadow: none;">Lihat</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-5">
                    <h3 style="font-weight: 700; color: #222; margin-bottom: 20px;">Catatan Penting:</h3>
                    <div style="background: white; border-left: 3px solid #ff7b00; padding: 25px 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                        <p style="color: #ff7b00; margin: 0; font-size: 16px;">Khusus Tambah/Ulang/Hapus Mata Kuliah silakan konfirmasi ke Admin HELLO SIC saat setelah mengisi</p>
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
