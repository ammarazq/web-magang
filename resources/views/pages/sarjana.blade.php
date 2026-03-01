@extends('layouts.app')

@section('title', 'Registrasi Vokasi/Sarjana - SALUT Insan Cendekia')

@push('styles')
<style>
    .card {
        border-radius: 3rem !important;
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
    <div class="card shadow p-4 mx-auto bg-light" style="max-width: 900px;">
        <h4 class="card-title text-center">Sistem Informasi Akademik</h4>
        <h5 class="card-text text-center">SALUT INSAN CENDEKIA</h5><br>
        <p class="card-text text-center text-primary">Pendaftaran Mahasiswa</p>

        <!-- WRAPPER AGAR KOTAK KECIL SEJAJAR -->
        <div class="d-flex gap-3 mt-3 mb-4 justify-content-center">
            <div class="card mx-3" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title text-center">Vokasi/Sarjana</h5>
                    <p class="card-text text-center">D3/D4/S1</p>
                    <div class="d-flex justify-content-center">
                        <a href="{{ url('/sarjana') }}" class="btn btn-outline-primary">Selengkapnya</a>
                    </div>
                </div>
            </div>

            <div class="card mx-3" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title text-center">Magister</h5>
                    <p class="card-text text-center">S2</p>
                    <div class="d-flex justify-content-center">
                        <a href="{{ url('/magister') }}" class="btn btn-outline-primary">Selengkapnya</a>
                    </div>
                </div>
            </div>

            <div class="card mx-3" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title text-center">Doktoral</h5>
                    <p class="card-text text-center">S3</p>
                    <div class="d-flex justify-content-center">
                        <a href="{{ url('/doktoral') }}" class="btn btn-outline-primary">Selengkapnya</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Terdapat kesalahan:</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form Pendaftaran -->
        <form action="{{ route('sarjana.submit') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan Nama Lengkap"data-bs-toggle="tooltip" data-bs-placement="top" title="Isi dengan nama lengkap anda" required>
            </div>

            <div class="mb-3">
                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Masukkan Tempat Lahir" data-bs-toggle="tooltip" data-bs-placement="top" title="Isi dengan tempat lahir anda" required>
            </div>

            <div class="mb-3">
                <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                <input type="date" 
                    class="form-control" 
                    id="tanggal_lahir" 
                    name="tanggal_lahir" 
                    data-bs-toggle="tooltip" 
                    data-bs-placement="top" 
                    title="Usia minimal 15 tahun" 
                    required>
                <div id="tanggalLahirError" class="text-danger mt-2 small fw-bold" style="display: none;">
                    <i class="fa fa-exclamation-circle"></i> Usia minimal 15 tahun! (Kelahiran maks: <span id="maxYearHint"></span>)
                </div>
                <div id="tanggalLahirSuccess" class="text-success mt-2 small fw-bold" style="display: none;">
                    <i class="fa fa-check-circle"></i> Usia memenuhi syarat (Umur Anda: <span id="calculatedAge"></span> tahun)
                </div>
            </div>

            <div class="mb-3">
                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" data-bs-toggle="tooltip" data-bs-placement="top" title="Jenis Kelamin" required>
                    <option selected value="">Pilih...</option>
                    <option value="P">Perempuan</option>
                    <option value="L">Laki-Laki</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="nama_ibu" class="form-label">Nama Ibu Kandung</label>
                <input type="text" class="form-control" id="nama_ibu" name="nama_ibu" placeholder="Masukkan Nama Ibu Kandung"data-bs-toggle="tooltip" data-bs-placement="top" title="Harap isi dengan nama Ibu Kandung anda" required>
            </div>

            <div class="mb-3">
                <label for="agama" class="form-label">Agama</label>
                <select class="form-select" id="agama" name="agama" data-bs-toggle="tooltip" data-bs-placement="top" title="Isi dengan agama yang anda yakini" required>
                    <option selected value="">Pilih...</option>
                    <option value="Islam">Islam</option>
                    <option value="Protestan">Protestan</option>
                    <option value="Katolik">Katolik</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Budha">Budha</option>
                    <option value="Konghucu">Konghucu</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan Alamat Lengkap" data-bs-toggle="tooltip" data-bs-placement="top" title="Harap isi dengan alamat lengkap anda" required></textarea>
            </div>

            <div class="mb-3">
                <label for="kewarganegaraan" class="form-label">Pilih Kewarganegaraan</label>
                <select class="form-select" id="kewarganegaraan" name="kewarganegaraan" data-bs-toggle="tooltip" data-bs-placement="top" title="Harap isi dengan kewarganegaraan anda" required>
                    <option selected value="">Pilih...</option>
                    <option value="WNI">WNI</option>
                    <option value="WNA">WNA</option>
                </select>
            </div>

            <!-- NIK untuk WNI -->
            <div class="mb-3 d-none" id="nikWrapper">
                <label for="nik" class="form-label">NIK</label>
                <input type="text"
                    class="form-control"
                    id="nik"
                    name="nik"
                    placeholder="Masukkan 16 digit NIK">
                <small class="text-muted d-block mt-1" id="nikHint">
                    <i class="fa fa-info-circle"></i> <span id="nikCounter">0</span>/16 digit
                </small>
            </div>

            <!-- Negara + Passport untuk WNA -->
            <div class="mb-3 d-none" id="pilihNegaraWrapper">
                <label for="pilihNegara" class="form-label">Pilih Negara</label>
                <select class="form-select" id="pilihNegara" name="negara" data-bs-toggle="tooltip" data-bs-placement="top" title="Pilih negara asal anda">
                    <option selected value="">Pilih...</option>
                    <option value="Timor Leste">Timor Leste</option>
                    <option value="Malaysia">Malaysia</option>
                    <option value="Singapura">Singapura</option>
                    <option value="Thailand">Thailand</option>
                    <option value="Filipina">Filipina</option>
                    <option value="Vietnam">Vietnam</option>
                    <option value="Myanmar">Myanmar</option>
                    <option value="Kamboja">Kamboja</option>
                    <option value="Laos">Laos</option>
                    <option value="Brunei">Brunei Darussalam</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div class="mb-3 d-none" id="passportWrapper">
                <label for="passport" class="form-label">Nomor Passport</label>
                <input type="text"
                    class="form-control"
                    id="passport"
                    name="passport"
                    placeholder="Masukkan Nomor Passport">
                <small class="text-muted d-block mt-1" id="passportHint">
                    <i class="fa fa-info-circle"></i> <span id="passportCounter">0</span>/15 karakter (min: 6)
                </small>
            </div>

            <div class="form-text mb-3">Pilih Jalur Program</div>
            <div class="d-flex gap-3 justify-content-center mb-4">
                <div class="card mx-3" style="width: 18rem;">
                    <div class="card-body" data-bs-toggle="tooltip" data-bs-placement="top" title="Program pengakuan sejumlah mata kuliah sebagai bentuk alih kredit nilai dari perguruan tinggi sebelumnya atau prestasi. (Untuk lulusan SLTA yang sudah pernah atau sudah luus kuliah dimanapun)" required>
                        <h5 class="card-title text-center">RPL</h5>
                        <p class="card-text text-center">Pengakuan Alih Kredit</p>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-outline-primary jalur-program" data-jalur="RPL">Pilih</button>
                        </div>
                    </div>
                </div>
                <div class="card mx-3" style="width: 18rem;">
                    <div class="card-body" data-bs-toggle="tooltip" data-bs-placement="top" title="Program penempuhan seluruh mata kuliah kurikulum dari semester awal sampai akhir (untuk lulusan SLTA yang belum kuliah dimana pun)">
                        <h5 class="card-title text-center">Non RPL</h5>
                        <p class="card-text text-center">Program Reguler</p>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-outline-primary jalur-program" data-jalur="Non RPL">Pilih</button>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="jalur_program" name="jalur_program" required>

            <div class="mb-3">
                <label for="jenjang" class="form-label">Pilih Jenjang Pendidikan yang akan ditempuh di UT</label>
                <select class="form-select" id="jenjang" name="jenjang"data-bs-toggle="tooltip" data-bs-placement="top" title="Pilih Jalur Program Terlebih Dahulu" required>
                    <option selected value="">Pilih...</option>
                    <option value="D3">D3</option>
                    <option value="D4">D4</option>
                    <option value="S1">S1</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="program_studi" class="form-label">Pilih Program Studi</label>
                <select class="form-select" id="program_studi" name="program_studi" data-bs-toggle="tooltip" data-bs-placement="top" title="Pilih Jenjang Pendidikan Terlebih Dahulu" required>
                    <option selected value="">Pilih...</option>
                    <option value="118">118 | Pendidikan Guru Sekolah Dasar Masukan Guru Dalam Jabatan (in service)</option>
                    <option value="122">122 | Pendidikan Guru Anak Usia Dini Masukan Guru Dalam jabatan (in service)</option>
                    <option value="163">163 | Teknologi Pendidikan - S1</option>
                    <option value="252">252 | Sistem Informasi - S1</option>
                    <option value="279">279 | Perencanaan Wilayah dan Kota - S1</option>
                    <option value="310">310 | Ilmu Perpustakaan - S1</option>
                    <option value="311">311 | Ilmu Hukum - S1</option>
                    <option value="458">458 | Ekonomi Syariah - S1</option>
                    <option value="471">471 | Pariwisata - S1</option>
                    <option value="483">483 | Akuntansi Keuangan Publik - S1</option>
                    <option value="50">50 | Ilmu Administrasi Negara - S1</option>
                    <option value="51">51 | Ilmu Administrasi Bisnis - S1</option>
                    <option value="53">53 | Ekonomi Pembangunan - S1</option>
                    <option value="54">54 | Manajemen - S1</option>
                    <option value="55">55 | Matematika - S1</option>
                    <option value="56">56 | Statistika - S1</option>
                    <option value="57">57 | Pendidikan Bahasa dan Sastra Indonesia - S1</option>
                    <option value="58">58 | Pendidikan Bahasa Inggris - S1</option>
                    <option value="59">59 | Pendidikan Biologi - S1</option>
                    <option value="60">60 | Pendidikan Fisika - S1</option>
                    <option value="61">61 | Pendidikan Kimia - S1</option>
                    <option value="62">62 | Pendidikan Matematika - S1</option>
                    <option value="70">70 | Sosiologi - S1</option>
                    <option value="71">71 | Ilmu Pemerintahan - S1</option>
                    <option value="72">72 | Ilmu Komunikasi - S1</option>
                    <option value="73">73 | Pendidikan Pancasila dan Kewarganegaraan - S1</option>
                    <option value="76">76 | Pendidikan Ekonomi - S1</option>
                    <option value="78">78 | Biologi - S1</option>
                    <option value="83">83 | Akuntansi - S1</option>
                    <option value="84">84 | Teknologi Pangan - S1</option>
                    <option value="87">87 | Sastra Inggris Bidang Minat Penerjemahan - S1</option>
                    <option value="274">274 | Agribisnis - S1</option>
                    <option value="151">151 | Pendidikan Agama Islam - S1</option>
                    <option value="312">312 | Perpajakan - S1</option>
                    <option value="253">253 | Sains Data - S1</option>
                    <option value="11A">11A | Pendidikan Guru Sekolah Dasar Masukan Guru Prajabatan (Pre Service) - S1</option>
                    <option value="12A">12A | Pendidikan Guru Anak Usia Dini Masukan Guru Prajabatan (Pre Service) - S1</option>
                    <option value="152">152 | Pendidikan Agama Islam Pre-Service - S1</option>
                    <option value="57A">57A | Pendidikan Bahasa dan Sastra Indonesia Pre - Service - S1</option>
                    <option value="58A">58A | Pendidikan Bahasa Inggris Pre-Service - S1</option>
                    <option value="59A">59A | Pendidikan Biologi Pre-Service - S1</option>
                    <option value="60A">60A | Pendidikan Fisika Pre-Service - S1</option>
                    <option value="61A">61A | Pendidikan Kimia Pre-Service - S1</option>
                    <option value="62A">62A | Pendidikan Matematika Pre-Service - S1</option>
                    <option value="73A">73A | Pendidikan Pancasila dan Kewarganegaraan - S1</option>
                    <option value="76A">76A | Pendidikan Ekonomi - S1</option>
                    <option value="472">472 | Kewirausahaan - S1</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="no_hp" class="form-label">No Handphone /WA</label>
                <input type="text"
                    class="form-control"
                    id="no_hp"
                    name="no_hp"
                    placeholder="Masukkan No Handphone / WA"
                    required>
                <small class="text-muted d-block mt-1" id="noHpHint">
                    <i class="fa fa-info-circle"></i> <span id="noHpCounter">0</span>/15 digit (min: 10)
                </small>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon3">@</span>
                    <input type="email" class="form-control" id="email" name="email" placeholder="contoh@gmail.com" required>
                </div>
                <small class="text-muted d-block mt-1">
                    <i class="fa fa-info-circle"></i> Email harus menggunakan domain <strong>@gmail.com</strong> (contoh: nama@gmail.com)
                </small>
                <div id="emailValidation" class="mt-1"></div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
                
                <!-- Card untuk Petunjuk Password -->
                <div class="card mt-3" style="background-color: #d1ecf1; border: 1px solid #bee5eb;">
                    <div class="card-body p-3">
                        <h6 class="card-title mb-2" style="color: #0c5460;">
                            <i class="fa fa-info-circle"></i> Petunjuk Password:
                        </h6>
                        <ul class="mb-0" style="font-size: 0.9rem; color: #0c5460;">
                            <li id="rule-lowercase" class="text-danger">Harus mengandung minimal satu huruf kecil</li>
                            <li id="rule-uppercase" class="text-danger">Harus mengandung minimal satu huruf besar</li>
                            <li id="rule-length" class="text-danger">Minimal 8 karakter</li>
                            <li id="rule-number" class="text-danger">Harus mengandung minimal satu angka</li>
                            <li id="rule-nodoublenum" class="text-danger">Tidak boleh meninputkan angka berurutan (misal: 123, 234)</li>
                            <li id="rule-nosequential" class="text-danger">Tidak boleh mengandung karakter berurut (misal: abc, xyz)</li>
                            <li id="rule-special" class="text-danger">Harus mengandung minimal satu karakter unik (!@#$%^&*)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Masukkan Konfirmasi Password" required>
                <div id="passwordMatch" class="mt-1"></div>
            </div>


            <!-- CAPTCHA Section -->
            <div class="mb-3">
                <label class="form-label">CAPTCHA - Silakan jawab pertanyaan berikut</label>
                <div class="d-flex align-items-center gap-3">
                    <div id="captchaQuestion" class="px-4 py-3 bg-light border rounded text-center" style="min-width: 120px;">
                        <span class="fw-bold fs-5 text-dark">{{ session('captcha_sarjana_num1', 0) }} + {{ session('captcha_sarjana_num2', 0) }}</span>
                    </div>
                    <span class="fw-bold fs-4">=</span>
                    <input type="number" id="captchaAnswer" name="captcha_answer" class="form-control" style="max-width: 150px;" placeholder="Jawaban" value="{{ old('captcha_answer') }}" required>
                    <button type="button" id="refreshCaptcha" class="btn btn-primary" title="Refresh CAPTCHA">
                        <i class="fa fa-refresh"></i> Refresh
                    </button>
                </div>
                @error('captcha_answer')
                    <div class="text-danger mt-2 small">
                        <i class="fa fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
                <small class="text-muted d-block mt-1">
                    <i class="fa fa-info-circle"></i> Masukkan hasil penjumlahan di atas
                </small>
            </div>

            <button type="submit" class="btn btn-success mt-3">Submit</button>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi NIK -->
<div class="modal fade" id="nikModal" tabindex="-1" aria-labelledby="nikModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="nikModalLabel">
                    <i class="fa fa-id-card"></i> Konfirmasi NIK
                </h5>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="fa fa-check-circle text-success" style="font-size: 3rem;"></i>
                </div>
                <h6 class="mb-3">NIK yang Anda masukkan:</h6>
                <div class="alert alert-info py-3">
                    <h4 class="mb-0 fw-bold" id="nikModalText"></h4>
                </div>
                <p class="text-muted small mb-0">Pastikan NIK yang Anda masukkan sudah benar</p>
            </div>
            <div class="modal-footer justify-content-center">
                <!-- <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="fa fa-check"></i> OK
                </button> -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Passport -->
<div class="modal fade" id="passportModal" tabindex="-1" aria-labelledby="passportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="passportModalLabel">
                    <i class="fa fa-passport"></i> Konfirmasi Passport
                </h5>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="fa fa-check-circle text-success" style="font-size: 3rem;"></i>
                </div>
                <h6 class="mb-3">Nomor Passport yang Anda masukkan:</h6>
                <div class="alert alert-info py-3">
                    <h4 class="mb-0 fw-bold" id="passportModalText"></h4>
                </div>
                <p class="text-muted small mb-0">Pastikan nomor passport yang Anda masukkan sudah benar</p>
            </div>
            <div class="modal-footer justify-content-center">
                <!-- <button type="button" class="btn btn-info" data-bs-dismiss="modal">
                    <i class="fa fa-check"></i> OK
                </button> -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Nomor HP -->
<div class="modal fade" id="noHpModal" tabindex="-1" aria-labelledby="noHpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="noHpModalLabel">
                    <i class="fa fa-phone"></i> Konfirmasi Nomor HP
                </h5>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="fa fa-check-circle text-success" style="font-size: 3rem;"></i>
                </div>
                <h6 class="mb-3">Nomor HP/WA yang Anda masukkan:</h6>
                <div class="alert alert-info py-3">
                    <h4 class="mb-0 fw-bold" id="noHpModalText"></h4>
                </div>
                <p class="text-muted small mb-0">Pastikan nomor HP/WA yang Anda masukkan sudah benar</p>
            </div>
            <div class="modal-footer justify-content-center">
                <!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                    <i class="fa fa-check"></i> OK
                </button> -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Persetujuan RPL -->
<div class="modal fade" id="rplModal" tabindex="-1" aria-labelledby="rplModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #003d82;">
                <h5 class="modal-title fw-bold text-center w-100" id="rplModalLabel">Persetujuan</h5>
            </div>
            <div class="modal-body py-4">
                <p class="mb-3">Pengusulan RPL dikenakan biaya Rp300.000 dan biaya admisi Rp.100.000. Jika ajuan RPL calon mahasiswa tidak disetujui maka biaya RPL tidak dapat dikembalikan.</p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="rplCheckbox">
                    <label class="form-check-label" for="rplCheckbox">
                        Saya Menyetujui
                    </label>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-2">
                <button type="button" class="btn btn-primary px-4" id="btnKembali">Kembali</button>
                <button type="button" class="btn btn-primary px-4" id="btnOk" style="background-color: #6fa3dc; border-color: #6fa3dc;" disabled>Ok</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Persetujuan Non RPL -->
<div class="modal fade" id="nonRplModal" tabindex="-1" aria-labelledby="nonRplModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #003d82;">
                <h5 class="modal-title fw-bold text-center w-100" id="nonRplModalLabel">Persetujuan</h5>
            </div>
            <div class="modal-body py-4">
                <p class="mb-3">Calon mahasiswa dikenakan biaya admisi Rp. 100.000.</p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="nonRplCheckbox">
                    <label class="form-check-label" for="nonRplCheckbox">
                        Saya Menyetujui
                    </label>
                </div>
            </div>
            <div class="modal-footer justify-content-center gap-2">
                <button type="button" class="btn btn-primary px-4" id="btnKembaliNonRpl">Kembali</button>
                <button type="button" class="btn btn-primary px-4" id="btnOkNonRpl" style="background-color: #6fa3dc; border-color: #6fa3dc;" disabled>Ok</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ============================================
        // VALIDASI TANGGAL LAHIR (UI FEEDBACK ONLY)
        // ============================================
        const dateInput = document.getElementById('tanggal_lahir');
        const ageError = document.getElementById('tanggalLahirError');
        const ageSuccess = document.getElementById('tanggalLahirSuccess');
        const maxYearHint = document.getElementById('maxYearHint');
        const calculatedAge = document.getElementById('calculatedAge');

        // Hitung batas tahun minimal (Tahun berjalan - 15)
        const currentYear = new Date().getFullYear();
        const maxAllowedYear = currentYear - 15;
        if (maxYearHint) {
            maxYearHint.textContent = maxAllowedYear;
        }

        // Fungsi untuk menampilkan umur (UI feedback, validasi tetap di backend)
        function showAgeInfo() {
            const selectedDateStr = dateInput.value;
            if (!selectedDateStr) {
                ageError.style.display = 'none';
                ageSuccess.style.display = 'none';
                return;
            }

            const birthDate = new Date(selectedDateStr);
            const today = new Date();
            
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            ageError.style.display = 'none';
            ageSuccess.style.display = 'none';

            if (age < 15) {
                ageError.style.display = 'block';
            } else {
                if (calculatedAge) {
                    calculatedAge.textContent = age;
                }
                ageSuccess.style.display = 'block';
            }
        }

        if (dateInput) {
            dateInput.addEventListener('change', showAgeInfo);
            dateInput.addEventListener('input', showAgeInfo);
        }

        // ============================================
        // CAPTCHA - AJAX REFRESH
        // ============================================
        const refreshCaptchaBtn = document.getElementById('refreshCaptcha');
        if (refreshCaptchaBtn) {
            refreshCaptchaBtn.addEventListener('click', function() {
                // Disable button dan tambahkan loading state
                this.disabled = true;
                const icon = this.querySelector('i');
                if (icon) {
                    icon.classList.add('fa-spin');
                }

                // Fetch CAPTCHA baru via AJAX
                fetch('{{ route("sarjana.captcha.refresh") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update pertanyaan CAPTCHA
                        const captchaQuestion = document.getElementById('captchaQuestion');
                        if (captchaQuestion) {
                            captchaQuestion.innerHTML = '<span class="fw-bold fs-5 text-dark">' + data.question + '</span>';
                        }
                        
                        // Clear input jawaban
                        const captchaAnswer = document.getElementById('captchaAnswer');
                        if (captchaAnswer) {
                            captchaAnswer.value = '';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error refreshing CAPTCHA:', error);
                    alert('Gagal memuat CAPTCHA baru. Silakan coba lagi.');
                })
                .finally(() => {
                    // Re-enable button dan hapus loading state
                    this.disabled = false;
                    if (icon) {
                        icon.classList.remove('fa-spin');
                    }
                });
            });
        }

        // ============================================
        // KEWARGANEGARAAN - SHOW/HIDE FIELDS
        // ============================================
        const kewarganegaraan = document.getElementById('kewarganegaraan');
        const negaraWrapper = document.getElementById('pilihNegaraWrapper');
        const passportWrapper = document.getElementById('passportWrapper');
        const nikWrapper = document.getElementById('nikWrapper');
        const nikInput = document.getElementById('nik');
        const passportInput = document.getElementById('passport');
        
        if (kewarganegaraan) {
            kewarganegaraan.addEventListener('change', function() {
                // Reset semua field
                if (negaraWrapper) negaraWrapper.classList.add('d-none');
                if (passportWrapper) passportWrapper.classList.add('d-none');
                if (nikWrapper) nikWrapper.classList.add('d-none');
                if (nikInput) nikInput.value = '';
                if (passportInput) passportInput.value = '';
                
                if (this.value === 'WNI') {
                    // Tampilkan NIK untuk WNI
                    if (nikWrapper) nikWrapper.classList.remove('d-none');
                } else if (this.value === 'WNA') {
                    // Tampilkan Negara + Passport untuk WNA
                    if (negaraWrapper) negaraWrapper.classList.remove('d-none');
                    if (passportWrapper) passportWrapper.classList.remove('d-none');
                }
            });
        }

        // ============================================
        // NIK INPUT - COUNTER & MODAL
        // ============================================
        if (nikInput) {
            const nikCounter = document.getElementById('nikCounter');
            const nikHint = document.getElementById('nikHint');
            
            nikInput.addEventListener('input', function() {
                // Hanya izinkan angka
                this.value = this.value.replace(/\D/g, '');
                
                // Batasi maksimal 16 digit
                if (this.value.length > 16) {
                    this.value = this.value.substring(0, 16);
                }
                
                // Update counter
                const currentLength = this.value.length;
                if (nikCounter) {
                    nikCounter.textContent = currentLength;
                }
                
                // Update warna hint berdasarkan jumlah karakter
                if (nikHint) {
                    if (currentLength === 0) {
                        nikHint.className = 'text-muted d-block mt-1';
                    } else if (currentLength < 16) {
                        nikHint.className = 'text-warning d-block mt-1 fw-bold';
                    } else if (currentLength === 16) {
                        nikHint.className = 'text-success d-block mt-1 fw-bold';
                    }
                }
                
                // Tampilkan modal saat tepat 16 digit
                if (currentLength === 16) {
                    const nikModalText = document.getElementById('nikModalText');
                    if (nikModalText) {
                        nikModalText.innerText = this.value;
                    }

                    const nikModal = document.getElementById('nikModal');
                    if (nikModal) {
                        new bootstrap.Modal(nikModal).show();
                    }
                }
            });
        }

        // ============================================
        // PASSPORT INPUT - COUNTER & MODAL
        // ============================================
        if (passportInput) {
            const passportCounter = document.getElementById('passportCounter');
            const passportHint = document.getElementById('passportHint');
            
            passportInput.addEventListener('input', function() {
                // Batasi maksimal 15 karakter
                if (this.value.length > 15) {
                    this.value = this.value.substring(0, 15);
                }
                
                // Update counter
                const currentLength = this.value.length;
                if (passportCounter) {
                    passportCounter.textContent = currentLength;
                }
                
                // Update warna hint berdasarkan jumlah karakter
                if (passportHint) {
                    if (currentLength === 0) {
                        passportHint.className = 'text-muted d-block mt-1';
                    } else if (currentLength < 6) {
                        passportHint.className = 'text-warning d-block mt-1 fw-bold';
                    } else if (currentLength >= 6 && currentLength <= 15) {
                        passportHint.className = 'text-success d-block mt-1 fw-bold';
                    }
                }
                
                // Tampilkan modal saat minimal 6 karakter (valid)
                if (currentLength >= 6 && currentLength <= 15) {
                    // Tampilkan modal hanya saat mencapai panjang tertentu (misal 6, 8, 10, dll)
                    // Atau bisa saat blur/kehilangan fokus
                }
            });

            // Tampilkan modal saat user selesai input (blur)
            passportInput.addEventListener('blur', function() {
                const currentLength = this.value.length;
                if (currentLength >= 6 && currentLength <= 15) {
                    const passportModalText = document.getElementById('passportModalText');
                    if (passportModalText) {
                        passportModalText.innerText = this.value;
                    }

                    const passportModal = document.getElementById('passportModal');
                    if (passportModal && this.value) {
                        new bootstrap.Modal(passportModal).show();
                    }
                }
            });
        }

        // ============================================
        // NOMOR HP INPUT - COUNTER & MODAL
        // ============================================
        const noHpInput = document.getElementById('no_hp');
        if (noHpInput) {
            const noHpCounter = document.getElementById('noHpCounter');
            const noHpHint = document.getElementById('noHpHint');
            
            noHpInput.addEventListener('input', function() {
                // Hanya izinkan angka
                this.value = this.value.replace(/\D/g, '');
                
                // Batasi maksimal 15 digit
                if (this.value.length > 15) {
                    this.value = this.value.substring(0, 15);
                }
                
                // Update counter
                const currentLength = this.value.length;
                if (noHpCounter) {
                    noHpCounter.textContent = currentLength;
                }
                
                // Update warna hint berdasarkan jumlah digit
                if (noHpHint) {
                    if (currentLength === 0) {
                        noHpHint.className = 'text-muted d-block mt-1';
                    } else if (currentLength < 10) {
                        noHpHint.className = 'text-warning d-block mt-1 fw-bold';
                    } else if (currentLength >= 10 && currentLength <= 15) {
                        noHpHint.className = 'text-success d-block mt-1 fw-bold';
                    }
                }
            });

            // Tampilkan modal saat user selesai input (blur) dan valid
            noHpInput.addEventListener('blur', function() {
                const currentLength = this.value.length;
                if (currentLength >= 10 && currentLength <= 15) {
                    const noHpModalText = document.getElementById('noHpModalText');
                    if (noHpModalText) {
                        noHpModalText.innerText = this.value;
                    }

                    const noHpModal = document.getElementById('noHpModal');
                    if (noHpModal && this.value) {
                        new bootstrap.Modal(noHpModal).show();
                    }
                }
            });
        }

        // ============================================
        // JALUR PROGRAM - BUTTON SELECTION
        // ============================================

        const rplModalElement = document.getElementById('rplModal');
        const nonRplModalElement = document.getElementById('nonRplModal');
        let rplModalInstance = null;
        let nonRplModalInstance = null;
        
        // Inisialisasi modal sekali saja
        if (rplModalElement) {
            rplModalInstance = new bootstrap.Modal(rplModalElement);
        }
        if (nonRplModalElement) {
            nonRplModalInstance = new bootstrap.Modal(nonRplModalElement);
        }

        document.querySelectorAll('.jalur-program').forEach(button => {
            button.addEventListener('click', function() {
                const jalur = this.getAttribute('data-jalur');
                const jalurInput = document.getElementById('jalur_program');
                if (jalurInput) jalurInput.value = jalur;
                
                document.querySelectorAll('.jalur-program').forEach(btn => {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary');
                });
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary');
                
                // Update pilihan jenjang berdasarkan jalur program
                updateJenjangOptions(jalur);
                
                // Jika RPL dipilih, tampilkan modal persetujuan RPL
                if (jalur === 'RPL' && rplModalInstance) {
                    rplModalInstance.show();
                }
                // Jika Non RPL dipilih, tampilkan modal persetujuan Non RPL
                else if (jalur === 'Non RPL' && nonRplModalInstance) {
                    nonRplModalInstance.show();
                }
            });
        });

        // ============================================
        // UPDATE JENJANG OPTIONS BASED ON JALUR PROGRAM
        // ============================================
        function updateJenjangOptions(jalur) {
            const jenjangSelect = document.getElementById('jenjang');
            if (!jenjangSelect) return;
            
            // Simpan nilai yang sudah dipilih (jika ada)
            const currentValue = jenjangSelect.value;
            
            // Hapus semua option kecuali yang pertama (Pilih...)
            jenjangSelect.innerHTML = '<option selected value="">Pilih...</option>';
            
            if (jalur === 'RPL') {
                // RPL hanya bisa S1
                const option = document.createElement('option');
                option.value = 'S1';
                option.textContent = 'S1';
                jenjangSelect.appendChild(option);
                
                // Auto select S1 untuk RPL
                jenjangSelect.value = 'S1';
                
                // Trigger change event untuk update program studi
                jenjangSelect.dispatchEvent(new Event('change'));
            } else if (jalur === 'Non RPL') {
                // Non RPL bisa D3, D4, S1
                const jenjangOptions = ['D3', 'D4', 'S1'];
                jenjangOptions.forEach(jenjang => {
                    const option = document.createElement('option');
                    option.value = jenjang;
                    option.textContent = jenjang;
                    jenjangSelect.appendChild(option);
                });
                
                // Restore nilai sebelumnya jika masih valid
                if (jenjangOptions.includes(currentValue)) {
                    jenjangSelect.value = currentValue;
                } else {
                    jenjangSelect.value = '';
                }
            }
        }

        // ============================================
        // MODAL RPL PERSETUJUAN
        // ============================================
        const rplCheckbox = document.getElementById('rplCheckbox');
        const btnOk = document.getElementById('btnOk');
        const btnKembali = document.getElementById('btnKembali');

        // Enable/disable tombol Ok berdasarkan checkbox
        if (rplCheckbox && btnOk) {
            rplCheckbox.addEventListener('change', function() {
                btnOk.disabled = !this.checked;
            });
        }

        // Tombol Ok - tutup modal dan tetap di jalur RPL
        if (btnOk) {
            btnOk.addEventListener('click', function() {
                // Tutup modal
                if (rplModalInstance) {
                    rplModalInstance.hide();
                }
                
                // Reset checkbox untuk penggunaan selanjutnya
                if (rplCheckbox) {
                    rplCheckbox.checked = false;
                }
                btnOk.disabled = true;
                
                // Pilihan RPL tetap tersimpan (tidak direset)
            });
        }

        // ============================================
        // VALIDASI EMAIL REAL-TIME (HARUS GMAIL.COM)
        // ============================================
        const emailInput = document.getElementById('email');
        const emailValidation = document.getElementById('emailValidation');

        if (emailInput && emailValidation) {
            emailInput.addEventListener('input', function() {
                const email = this.value.trim();
                // Pattern untuk memvalidasi email yang harus berakhiran @gmail.com
                const gmailPattern = /^[a-zA-Z0-9._-]+@gmail\.com$/;
                
                if (email === '') {
                    emailValidation.innerHTML = '';
                    emailInput.classList.remove('is-valid', 'is-invalid');
                } else if (gmailPattern.test(email)) {
                    emailValidation.innerHTML = '<small class="text-success"><i class="fa fa-check-circle"></i> Email gmail.com valid</small>';
                    emailInput.classList.remove('is-invalid');
                    emailInput.classList.add('is-valid');
                } else {
                    // Cek apakah user salah ketik gmail (misal: gmai, gmial, dll)
                    if (email.includes('@') && !email.endsWith('@gmail.com')) {
                        const domain = email.split('@')[1];
                        if (domain && domain.includes('gma') || domain && domain.includes('gmi')) {
                            emailValidation.innerHTML = '<small class="text-danger"><i class="fa fa-times-circle"></i> Salah ketik? Harus menggunakan <strong>@gmail.com</strong></small>';
                        } else {
                            emailValidation.innerHTML = '<small class="text-danger"><i class="fa fa-times-circle"></i> Email harus menggunakan domain <strong>@gmail.com</strong></small>';
                        }
                    } else {
                        emailValidation.innerHTML = '<small class="text-danger"><i class="fa fa-times-circle"></i> Format email tidak valid. Harus: nama@gmail.com</small>';
                    }
                    emailInput.classList.remove('is-valid');
                    emailInput.classList.add('is-invalid');
                }
            });
        }

        // ============================================
        // VALIDASI PASSWORD KOMPLEKS REAL-TIME
        // ============================================
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        const passwordMatchDiv = document.getElementById('passwordMatch');

        // Fungsi untuk mengecek urutan angka
        function hasSequentialNumbers(str) {
            for (let i = 0; i < str.length - 2; i++) {
                const char1 = str.charCodeAt(i);
                const char2 = str.charCodeAt(i + 1);
                const char3 = str.charCodeAt(i + 2);
                
                // Cek urutan naik (123, 234, dll)
                if (char2 === char1 + 1 && char3 === char2 + 1) {
                    return true;
                }
                // Cek urutan turun (321, 210, dll)
                if (char2 === char1 - 1 && char3 === char2 - 1) {
                    return true;
                }
            }
            return false;
        }

        // Fungsi untuk mengecek urutan huruf
        function hasSequentialLetters(str) {
            const lower = str.toLowerCase();
            for (let i = 0; i < lower.length - 2; i++) {
                const char1 = lower.charCodeAt(i);
                const char2 = lower.charCodeAt(i + 1);
                const char3 = lower.charCodeAt(i + 2);
                
                // Cek urutan naik (abc, bcd, xyz, dll)
                if (char2 === char1 + 1 && char3 === char2 + 1) {
                    return true;
                }
                // Cek urutan turun (cba, zyx, dll)
                if (char2 === char1 - 1 && char3 === char2 - 1) {
                    return true;
                }
            }
            return false;
        }

        // Validasi password real-time
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                
                // Rule: Minimal 1 huruf kecil
                const hasLowercase = /[a-z]/.test(password);
                updateRule('rule-lowercase', hasLowercase, 'Harus mengandung minimal satu huruf kecil');
                
                // Rule: Minimal 1 huruf besar
                const hasUppercase = /[A-Z]/.test(password);
                updateRule('rule-uppercase', hasUppercase, 'Harus mengandung minimal satu huruf besar');
                
                // Rule: Minimal 8 karakter
                const hasMinLength = password.length >= 8;
                updateRule('rule-length', hasMinLength, 'Minimal 8 karakter');
                
                // Rule: Minimal 1 angka
                const hasNumber = /[0-9]/.test(password);
                updateRule('rule-number', hasNumber, 'Harus mengandung minimal satu angka');
                
                // Rule: Tidak ada angka berurutan
                const noSequentialNum = !hasSequentialNumbers(password);
                updateRule('rule-nodoublenum', noSequentialNum, 'Tidak boleh meninputkan angka berurutan (misal: 123, 234)');
                
                // Rule: Tidak ada huruf berurutan
                const noSequentialLetter = !hasSequentialLetters(password);
                updateRule('rule-nosequential', noSequentialLetter, 'Tidak boleh mengandung karakter berurut (misal: abc, xyz)');
                
                // Rule: Minimal 1 karakter spesial
                const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
                updateRule('rule-special', hasSpecial, 'Harus mengandung minimal satu karakter unik (!@#$%^&*)');
                
                // Cek match dengan confirm password
                checkPasswordMatch();
            });
        }

        // Validasi confirm password
        if (passwordConfirmInput) {
            passwordConfirmInput.addEventListener('input', checkPasswordMatch);
        }

        // Fungsi untuk update rule visual
        function updateRule(elementId, isValid, message) {
            const element = document.getElementById(elementId);
            if (element) {
                if (isValid) {
                    element.className = 'text-success';
                    element.innerHTML = '✓ ' + message;
                } else {
                    element.className = 'text-danger';
                    element.innerHTML = '✗ ' + message;
                }
            }
        }

        // Fungsi untuk cek kecocokan password
        function checkPasswordMatch() {
            if (!passwordInput || !passwordConfirmInput || !passwordMatchDiv) return;
            
            const password = passwordInput.value;
            const confirmPassword = passwordConfirmInput.value;
            
            if (confirmPassword === '') {
                passwordMatchDiv.innerHTML = '';
                passwordConfirmInput.classList.remove('is-valid', 'is-invalid');
            } else if (password === confirmPassword) {
                passwordMatchDiv.innerHTML = '<small class="text-success"><i class="fa fa-check-circle"></i> Password cocok</small>';
                passwordConfirmInput.classList.remove('is-invalid');
                passwordConfirmInput.classList.add('is-valid');
            } else {
                passwordMatchDiv.innerHTML = '<small class="text-danger"><i class="fa fa-times-circle"></i> Password tidak cocok</small>';
                passwordConfirmInput.classList.remove('is-valid');
                passwordConfirmInput.classList.add('is-invalid');
            }
        }

        // Tombol Kembali - reset pilihan jalur program dan tutup modal
        if (btnKembali) {
            btnKembali.addEventListener('click', function() {
                // Reset pilihan jalur program
                const jalurInput = document.getElementById('jalur_program');
                if (jalurInput) jalurInput.value = '';
                
                // Reset semua tombol jalur program
                document.querySelectorAll('.jalur-program').forEach(btn => {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary');
                });
                
                // Reset pilihan jenjang
                const jenjangSelect = document.getElementById('jenjang');
                if (jenjangSelect) {
                    jenjangSelect.innerHTML = '<option selected value="">Pilih...</option><option value="D3">D3</option><option value="D4">D4</option><option value="S1">S1</option>';
                }
                
                // Reset checkbox
                if (rplCheckbox) {
                    rplCheckbox.checked = false;
                }
                if (btnOk) {
                    btnOk.disabled = true;
                }
                
                // Tutup modal
                if (rplModalInstance) {
                    rplModalInstance.hide();
                }
            });
        }

        // ============================================
        // MODAL NON RPL PERSETUJUAN
        // ============================================
        const nonRplCheckbox = document.getElementById('nonRplCheckbox');
        const btnOkNonRpl = document.getElementById('btnOkNonRpl');
        const btnKembaliNonRpl = document.getElementById('btnKembaliNonRpl');

        // Enable/disable tombol Ok berdasarkan checkbox
        if (nonRplCheckbox && btnOkNonRpl) {
            nonRplCheckbox.addEventListener('change', function() {
                btnOkNonRpl.disabled = !this.checked;
            });
        }

        // Tombol Ok - tutup modal dan tetap di jalur Non RPL
        if (btnOkNonRpl) {
            btnOkNonRpl.addEventListener('click', function() {
                // Tutup modal
                if (nonRplModalInstance) {
                    nonRplModalInstance.hide();
                }
                
                // Reset checkbox untuk penggunaan selanjutnya
                if (nonRplCheckbox) {
                    nonRplCheckbox.checked = false;
                }
                btnOkNonRpl.disabled = true;
                
                // Pilihan Non RPL tetap tersimpan (tidak direset)
            });
        }

        // Tombol Kembali - reset pilihan jalur program dan tutup modal
        if (btnKembaliNonRpl) {
            btnKembaliNonRpl.addEventListener('click', function() {
                // Reset pilihan jalur program
                const jalurInput = document.getElementById('jalur_program');
                if (jalurInput) jalurInput.value = '';
                
                // Reset semua tombol jalur program
                document.querySelectorAll('.jalur-program').forEach(btn => {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary');
                });
                
                // Reset pilihan jenjang
                const jenjangSelect = document.getElementById('jenjang');
                if (jenjangSelect) {
                    jenjangSelect.innerHTML = '<option selected value="">Pilih...</option><option value="D3">D3</option><option value="D4">D4</option><option value="S1">S1</option>';
                }
                
                // Reset checkbox
                if (nonRplCheckbox) {
                    nonRplCheckbox.checked = false;
                }
                if (btnOkNonRpl) {
                    btnOkNonRpl.disabled = true;
                }
                
                // Tutup modal
                if (nonRplModalInstance) {
                    nonRplModalInstance.hide();
                }
            });
        }
    });
</script>
@endpush
