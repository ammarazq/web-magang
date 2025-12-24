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
                    class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                    id="tanggal_lahir" 
                    name="tanggal_lahir" 
                    value="{{ old('tanggal_lahir') }}" 
                    data-bs-toggle="tooltip" 
                    data-bs-placement="top" 
                    title="Usia minimal 15 tahun" 
                    required>
                @error('tanggal_lahir')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
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
            <div class="mb-3 d-none position-relative" id="nikWrapper">
                <label for="nik" class="form-label">NIK</label>
                <input type="text"
                    class="form-control"
                    id="nik"
                    name="nik"
                    placeholder="Masukkan 16 digit NIK">
                
                <!-- Tooltip Fixed Position -->
                <div class="position-absolute d-none" id="nikTooltip" style="top: 8px; right: -180px; z-index: 1000;">
                    <div class="alert alert-info mb-0 py-2 px-3 shadow-sm" style="border-radius: 8px; font-size: 0.875rem;">
                        <i class="fa fa-info-circle"></i> <strong>Maksimal 16 Karakter</strong>
                    </div>
                </div>
                
                <small class="text-muted d-block mt-1" id="nikHint">
                    <i class="fa fa-info-circle"></i> <span id="nikCounter">0</span>/16 digit
                </small>
                <small class="text-danger d-none" id="nikError">
                    NIK harus terdiri dari 16 digit angka
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

            <div class="mb-3 d-none position-relative" id="passportWrapper">
                <label for="passport" class="form-label">Nomor Passport</label>
                <input type="text"
                    class="form-control"
                    id="passport"
                    name="passport"
                    placeholder="Masukkan Nomor Passport">
                
                <!-- Tooltip Fixed Position -->
                <div class="position-absolute d-none" id="passportTooltip" style="top: 8px; right: -180px; z-index: 1000;">
                    <div class="alert alert-info mb-0 py-2 px-3 shadow-sm" style="border-radius: 8px; font-size: 0.875rem;">
                        <i class="fa fa-info-circle"></i> <strong>Maksimal 15 Karakter</strong>
                    </div>
                </div>
                
                <small class="text-muted d-block mt-1" id="passportHint">
                    <i class="fa fa-info-circle"></i> <span id="passportCounter">0</span>/15 digit
                </small>
                <small class="text-danger d-none" id="passportError">
                    Nomor Passport maksimal 15 karakter
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

            <div class="mb-3 position-relative">
                <label for="no_hp" class="form-label">No Handphone /WA</label>
                <input type="text"
                    class="form-control"
                    id="no_hp"
                    name="no_hp"
                    placeholder="Masukkan No Handphone / WA">
                
                <!-- Tooltip Fixed Position -->
                <div class="position-absolute d-none" id="noHpTooltip" style="top: 8px; right: -180px; z-index: 1000;">
                    <div class="alert alert-info mb-0 py-2 px-3 shadow-sm" style="border-radius: 8px; font-size: 0.875rem;">
                        <i class="fa fa-info-circle"></i> <strong>Maksimal 15 Digit</strong>
                    </div>
                </div>
                
                <small class="text-muted d-block mt-1" id="noHpHint">
                    <i class="fa fa-info-circle"></i> <span id="noHpCounter">0</span>/15 digit
                </small>
                <small class="text-danger d-none" id="noHpError">
                    Nomor HP harus berupa angka maksimal 15 digit
                </small>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon3">@</span>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email" data-bs-toggle="tooltip" data-bs-placement="top" title="Harap isi dengan E-mail Anda" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" data-bs-toggle="tooltip" data-bs-placement="top" title="Harap isi dengan Password Anda" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Masukkan Konfirmasi Password" data-bs-toggle="tooltip" data-bs-pplacement="top" title="Harap isi konfirmasi password anda" required>
            </div>


            <div class="mb-3">
                <label class="form-label">Silakan jawab pertanyaan berikut</label>
                <div class="d-flex align-items-center gap-3">
                    <div id="captchaQuestion" class="px-5 py-2 bg-light border rounded text-secondary fw-semibold">
                        1 + 17
                    </div>
                    <span class="fw-bold">=</span>
                    <input type="text" id="captchaAnswer" name="captcha_answer" class="form-control" placeholder="Jawaban Anda" required>
                    <button type="button" class="btn btn-primary" onclick="generateMathCaptcha()">
                        <i class="fa fa-refresh"></i>
                    </button>
                </div>
                <small id="captchaMessage" class="text-danger mt-2 d-block"></small>
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
                <!-- <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button> -->
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
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-edit"></i> Edit
                </button> -->
                <!-- <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="fa fa-check"></i> Benar
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
    // ============================================
    // VALIDASI TANGGAL LAHIR DINAMIS
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('tanggal_lahir');
        const ageError = document.getElementById('tanggalLahirError');
        const ageSuccess = document.getElementById('tanggalLahirSuccess');
        const maxYearHint = document.getElementById('maxYearHint');
        const calculatedAge = document.getElementById('calculatedAge');
        const submitBtn = document.querySelector('button[type="submit"]');

        // Hitung batas tahun minimal (Tahun berjalan - 15)
        const currentYear = new Date().getFullYear();
        const maxAllowedYear = currentYear - 15;
        if (maxYearHint) {
            maxYearHint.textContent = maxAllowedYear;
        }

        // Fungsi Validasi Dinamis
        function validateAge() {
            const selectedDateStr = dateInput.value;
            if (!selectedDateStr) {
                // Sembunyikan pesan jika tidak ada input
                ageError.style.display = 'none';
                ageSuccess.style.display = 'none';
                dateInput.classList.remove('is-invalid', 'is-valid');
                if (submitBtn) submitBtn.disabled = false;
                return true;
            }

            const birthDate = new Date(selectedDateStr);
            const today = new Date();
            
            // Hitung umur secara presisi
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            // Koreksi jika bulan/hari belum lewat di tahun berjalan
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            // Update tahun maksimal
            if (maxYearHint) {
                maxYearHint.textContent = maxAllowedYear;
            }

            // Reset state tampilan
            dateInput.classList.remove('is-invalid', 'is-valid');
            ageError.style.display = 'none';
            ageSuccess.style.display = 'none';

            if (age < 15) {
                // JIKA TIDAK VALID
                dateInput.classList.add('is-invalid');
                ageError.style.display = 'block';
                if (submitBtn) submitBtn.disabled = true;
                return false;
            } else {
                // JIKA VALID
                if (calculatedAge) {
                    calculatedAge.textContent = age;
                }
                dateInput.classList.add('is-valid');
                ageSuccess.style.display = 'block';
                if (submitBtn) submitBtn.disabled = false;
                return true;
            }
        }

        // Jalankan validasi setiap kali nilai input berubah (Dinamis)
        if (dateInput) {
            dateInput.addEventListener('change', validateAge);
            dateInput.addEventListener('input', validateAge);
        }

        // ============================================
        // CAPTCHA
        // ============================================
        let correctAnswer = 18;

        function generateMathCaptcha() {
            const num1 = Math.floor(Math.random() * 20) + 1;
            const num2 = Math.floor(Math.random() * 20) + 1;
            correctAnswer = num1 + num2;
            
            const captchaQuestion = document.getElementById('captchaQuestion');
            const captchaAnswer = document.getElementById('captchaAnswer');
            const captchaMessage = document.getElementById('captchaMessage');
            
            if (captchaQuestion) captchaQuestion.textContent = num1 + ' + ' + num2;
            if (captchaAnswer) captchaAnswer.value = '';
            if (captchaMessage) captchaMessage.textContent = '';
        }

        function validateMathCaptcha() {
            const userAnswer = parseInt(document.getElementById('captchaAnswer').value);
            const messageEl = document.getElementById('captchaMessage');
            
            if (userAnswer === correctAnswer) {
                messageEl.textContent = 'Captcha benar!';
                messageEl.classList.remove('text-danger');
                messageEl.classList.add('text-success');
                return true;
            } else {
                messageEl.textContent = 'Captcha salah! Silakan coba lagi.';
                messageEl.classList.remove('text-success');
                messageEl.classList.add('text-danger');
                return false;
            }
        }

        // Generate captcha saat halaman dimuat
        generateMathCaptcha();

        // Expose function untuk tombol refresh captcha
        window.generateMathCaptcha = generateMathCaptcha;

        // ============================================
        // VALIDASI NIK
        // ============================================
        function validateNIK() {
            const kewarganegaraanValue = kewarganegaraan ? kewarganegaraan.value : '';
            
            // Hanya validasi jika WNI
            if (kewarganegaraanValue !== 'WNI') {
                return true;
            }
            
            const nikValue = nikInput ? nikInput.value : '';
            
            // Jika kosong, tidak perlu validasi (tidak wajib tampilkan error)
            if (!nikValue) {
                if (nikError) {
                    nikError.textContent = 'NIK wajib diisi untuk WNI';
                    nikError.classList.remove('d-none');
                }
                if (nikInput) {
                    nikInput.classList.add('is-invalid');
                    nikInput.classList.remove('is-valid');
                }
                return false;
            }
            
            // Validasi lebih dari 16 digit
            if (nikValue.length > 16) {
                if (nikError) {
                    nikError.textContent = 'NIK maksimal 16 digit! Anda memasukkan ' + nikValue.length + ' digit';
                    nikError.classList.remove('d-none');
                }
                if (nikInput) {
                    nikInput.classList.add('is-invalid');
                    nikInput.classList.remove('is-valid');
                }
                return false;
            }
            
            // Validasi kurang dari 16 digit
            if (nikValue.length < 16) {
                if (nikError) {
                    nikError.textContent = 'NIK harus terdiri dari 16 digit angka';
                    nikError.classList.remove('d-none');
                }
                if (nikInput) {
                    nikInput.classList.add('is-invalid');
                    nikInput.classList.remove('is-valid');
                }
                return false;
            }
            
            // Valid (tepat 16 digit)
            if (nikError) nikError.classList.add('d-none');
            if (nikInput) {
                nikInput.classList.remove('is-invalid');
                nikInput.classList.add('is-valid');
            }
            return true;
        }

        // ============================================
        // VALIDASI NOMOR HP
        // ============================================
        function validateNoHp() {
            const noHpInput = document.getElementById('no_hp');
            const noHpValue = noHpInput ? noHpInput.value : '';
            const noHpError = document.getElementById('noHpError');
            
            // Jika kosong, tidak perlu validasi (tidak wajib tampilkan error)
            if (!noHpValue) {
                if (noHpError) {
                    noHpError.textContent = 'Nomor HP wajib diisi';
                    noHpError.classList.remove('d-none');
                }
                if (noHpInput) {
                    noHpInput.classList.add('is-invalid');
                    noHpInput.classList.remove('is-valid');
                }
                return false;
            }
            
            // Validasi lebih dari 15 digit
            if (noHpValue.length > 15) {
                if (noHpError) {
                    noHpError.textContent = 'Nomor HP maksimal 15 digit! Anda memasukkan ' + noHpValue.length + ' digit';
                    noHpError.classList.remove('d-none');
                }
                if (noHpInput) {
                    noHpInput.classList.add('is-invalid');
                    noHpInput.classList.remove('is-valid');
                }
                return false;
            }
            
            // Validasi kurang dari 10 digit (minimal nomor HP Indonesia)
            if (noHpValue.length < 10) {
                if (noHpError) {
                    noHpError.textContent = 'Nomor HP minimal 10 digit';
                    noHpError.classList.remove('d-none');
                }
                if (noHpInput) {
                    noHpInput.classList.add('is-invalid');
                    noHpInput.classList.remove('is-valid');
                }
                return false;
            }
            
            // Valid (10-15 digit)
            if (noHpError) noHpError.classList.add('d-none');
            if (noHpInput) {
                noHpInput.classList.remove('is-invalid');
                noHpInput.classList.add('is-valid');
            }
            return true;
        }

        // ============================================
        // VALIDASI PASSPORT
        // ============================================
        function validatePassport() {
            const kewarganegaraanValue = kewarganegaraan ? kewarganegaraan.value : '';
            
            // Hanya validasi jika WNA
            if (kewarganegaraanValue !== 'WNA') {
                return true;
            }
            
            const passportValue = passportInput ? passportInput.value : '';
            
            // Jika kosong, tidak perlu validasi (tidak wajib tampilkan error)
            if (!passportValue) {
                const passportError = document.getElementById('passportError');
                if (passportError) {
                    passportError.textContent = 'Nomor Passport wajib diisi untuk WNA';
                    passportError.classList.remove('d-none');
                }
                if (passportInput) {
                    passportInput.classList.add('is-invalid');
                    passportInput.classList.remove('is-valid');
                }
                return false;
            }
            
            // Validasi lebih dari 15 karakter
            if (passportValue.length > 15) {
                const passportError = document.getElementById('passportError');
                if (passportError) {
                    passportError.textContent = 'Nomor Passport maksimal 15 karakter! Anda memasukkan ' + passportValue.length + ' karakter';
                    passportError.classList.remove('d-none');
                }
                if (passportInput) {
                    passportInput.classList.add('is-invalid');
                    passportInput.classList.remove('is-valid');
                }
                return false;
            }
            
            // Valid
            const passportError = document.getElementById('passportError');
            if (passportError) passportError.classList.add('d-none');
            if (passportInput) {
                passportInput.classList.remove('is-invalid');
                passportInput.classList.add('is-valid');
            }
            return true;
        }

        // ============================================
        // FORM SUBMIT VALIDATION
        // ============================================
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                if (!validateAge()) {
                    e.preventDefault();
                    dateInput.focus();
                    isValid = false;
                }
                
                if (!validateNIK()) {
                    e.preventDefault();
                    if (nikInput) nikInput.focus();
                    isValid = false;
                }
                
                if (!validatePassport()) {
                    e.preventDefault();
                    if (passportInput) passportInput.focus();
                    isValid = false;
                }
                
                if (!validateNoHp()) {
                    e.preventDefault();
                    const noHpInput = document.getElementById('no_hp');
                    if (noHpInput) noHpInput.focus();
                    isValid = false;
                }
                
                if (!validateMathCaptcha()) {
                    e.preventDefault();
                    isValid = false;
                }
                
                if (!isValid) {
                    return false;
                }
            });
        }

        // ============================================
        // KEWARGANEGARAAN & NIK / PASSPORT
        // ============================================
        const kewarganegaraan = document.getElementById('kewarganegaraan');
        const negaraWrapper = document.getElementById('pilihNegaraWrapper');
        const passportWrapper = document.getElementById('passportWrapper');
        const nikWrapper = document.getElementById('nikWrapper');
        const nikInput = document.getElementById('nik');
        const nikError = document.getElementById('nikError');
        const passportInput = document.getElementById('passport');
        

        if (kewarganegaraan) {
            kewarganegaraan.addEventListener('change', function() {
                // Reset semua field
                if (negaraWrapper) negaraWrapper.classList.add('d-none');
                if (passportWrapper) passportWrapper.classList.add('d-none');
                if (nikWrapper) nikWrapper.classList.add('d-none');
                if (nikInput) nikInput.value = '';
                if (passportInput) passportInput.value = '';
                if (nikError) nikError.classList.add('d-none');
                
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

        if (nikInput) {
            const nikCounter = document.getElementById('nikCounter');
            const nikHint = document.getElementById('nikHint');
            const nikTooltip = document.getElementById('nikTooltip');
            
            // Tampilkan tooltip saat focus
            nikInput.addEventListener('focus', function() {
                if (nikTooltip) {
                    nikTooltip.classList.remove('d-none');
                }
            });
            
            // Sembunyikan tooltip saat blur
            nikInput.addEventListener('blur', function() {
                if (nikTooltip) {
                    nikTooltip.classList.add('d-none');
                }
                validateNIK();
            });
            
            nikInput.addEventListener('input', function() {
                // Hanya izinkan angka
                this.value = this.value.replace(/\D/g, '');
                
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
                    } else if (currentLength > 16) {
                        nikHint.className = 'text-danger d-block mt-1 fw-bold';
                    }
                }
                
                // Tampilkan error hanya jika lebih dari 16 digit
                if (currentLength > 16) {
                    if (nikError) {
                        nikError.textContent = 'NIK maksimal 16 digit! Anda memasukkan ' + currentLength + ' digit';
                        nikError.classList.remove('d-none');
                    }
                    if (this) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    }
                } else if (currentLength === 16) {
                    // Tepat 16 digit - valid
                    if (nikError) nikError.classList.add('d-none');
                    if (this) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                    
                    // Tampilkan modal
                    const nikModalText = document.getElementById('nikModalText');
                    if (nikModalText) {
                        nikModalText.innerText = 'NIK anda adalah ' + this.value;
                    }

                    const nikModal = document.getElementById('nikModal');
                    if (nikModal) {
                        new bootstrap.Modal(nikModal).show();
                    }
                } else {
                    // Kurang dari 16 - sembunyikan error, hapus validasi visual
                    if (nikError) nikError.classList.add('d-none');
                    if (this) {
                        this.classList.remove('is-invalid', 'is-valid');
                    }
                }
            });
        }

        // ============================================
        // VALIDASI PASSPORT INPUT
        // ============================================
        if (passportInput) {
            const passportCounter = document.getElementById('passportCounter');
            const passportHint = document.getElementById('passportHint');
            const passportTooltip = document.getElementById('passportTooltip');
            const passportError = document.getElementById('passportError');
            
            // Tampilkan tooltip saat focus
            passportInput.addEventListener('focus', function() {
                if (passportTooltip) {
                    passportTooltip.classList.remove('d-none');
                }
            });
            
            // Sembunyikan tooltip saat blur
            passportInput.addEventListener('blur', function() {
                if (passportTooltip) {
                    passportTooltip.classList.add('d-none');
                }
                validatePassport();
            });
            
            passportInput.addEventListener('input', function() {
                // Update counter
                const currentLength = this.value.length;
                if (passportCounter) {
                    passportCounter.textContent = currentLength;
                }
                
                // Update warna hint berdasarkan jumlah karakter
                if (passportHint) {
                    if (currentLength === 0) {
                        passportHint.className = 'text-muted d-block mt-1';
                    } else if (currentLength < 15) {
                        passportHint.className = 'text-warning d-block mt-1 fw-bold';
                    } else if (currentLength === 15) {
                        passportHint.className = 'text-success d-block mt-1 fw-bold';
                    } else if (currentLength > 15) {
                        passportHint.className = 'text-danger d-block mt-1 fw-bold';
                    }
                }
                
                // Tampilkan error hanya jika lebih dari 15 karakter
                if (currentLength > 15) {
                    if (passportError) {
                        passportError.textContent = 'Nomor Passport maksimal 15 karakter! Anda memasukkan ' + currentLength + ' karakter';
                        passportError.classList.remove('d-none');
                    }
                    if (this) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    }
                } else if (currentLength >= 6 && currentLength <= 15) {
                    // Valid (minimal 6, maksimal 15 karakter)
                    if (passportError) passportError.classList.add('d-none');
                    if (this) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                } else {
                    // Kurang dari 6 - sembunyikan error, hapus validasi visual
                    if (passportError) passportError.classList.add('d-none');
                    if (this) {
                        this.classList.remove('is-invalid', 'is-valid');
                    }
                }
            });
        }

        // ============================================
        // VALIDASI NOMOR HP INPUT
        // ============================================
        const noHpInput = document.getElementById('no_hp');
        if (noHpInput) {
            const noHpCounter = document.getElementById('noHpCounter');
            const noHpHint = document.getElementById('noHpHint');
            const noHpTooltip = document.getElementById('noHpTooltip');
            const noHpError = document.getElementById('noHpError');
            
            // Tampilkan tooltip saat focus
            noHpInput.addEventListener('focus', function() {
                if (noHpTooltip) {
                    noHpTooltip.classList.remove('d-none');
                }
            });
            
            // Sembunyikan tooltip saat blur
            noHpInput.addEventListener('blur', function() {
                if (noHpTooltip) {
                    noHpTooltip.classList.add('d-none');
                }
                validateNoHp();
            });
            
            noHpInput.addEventListener('input', function() {
                // Hanya izinkan angka
                this.value = this.value.replace(/\D/g, '');
                
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
                    } else if (currentLength > 15) {
                        noHpHint.className = 'text-danger d-block mt-1 fw-bold';
                    }
                }
                
                // Tampilkan error hanya jika lebih dari 15 digit
                if (currentLength > 15) {
                    if (noHpError) {
                        noHpError.textContent = 'Nomor HP maksimal 15 digit! Anda memasukkan ' + currentLength + ' digit';
                        noHpError.classList.remove('d-none');
                    }
                    if (this) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    }
                } else if (currentLength >= 10 && currentLength <= 15) {
                    // Valid (10-15 digit)
                    if (noHpError) noHpError.classList.add('d-none');
                    if (this) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                } else {
                    // Kurang dari 10 - sembunyikan error, hapus validasi visual
                    if (noHpError) noHpError.classList.add('d-none');
                    if (this) {
                        this.classList.remove('is-invalid', 'is-valid');
                    }
                }
            });
        }

        // ============================================
        // JALUR PROGRAM
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
