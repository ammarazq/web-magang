@extends('layouts.app')

@section('title', 'Registrasi Doktoral (S3) - SALUT Insan Cendekia')

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
        <p class="card-text text-center text-primary">Pendaftaran Mahasiswa Doktoral</p>

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

        <!-- Form Pendaftaran Doktoral -->
        <form action="{{ route('doktoral.submit') }}" method="POST">
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
                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Usia minimal 15 tahun" required>
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
                <label class="form-label d-block">Jenis Kelamin</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkL" value="L" data-bs-toggle="tooltip" data-bs-placement="top" title="Jenis Kelamin" required>
                    <label class="form-check-label" for="jkL">Laki-laki</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkP" value="P" data-bs-toggle="tooltip" data-bs-placement="top" title="Jenis Kelamin" required>
                    <label class="form-check-label" for="jkP">Perempuan</label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label d-block">Status Kawin</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status_kawin" id="skKawin" value="Kawin" data-bs-toggle="tooltip" data-bs-placement="top" title="Status Kawin" required>
                    <label class="form-check-label" for="skKawin">Kawin</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status_kawin" id="skBelumKawin" value="Belum Kawin" data-bs-toggle="tooltip" data-bs-placement="top" title="Status Kawin" required>
                    <label class="form-check-label" for="skBelumKawin">Belum Kawin</label>
                </div>
            </div>

            <div class="mb-3">
                <label for="nik" class="form-label">NIK</label>
                <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukkan NIK" data-bs-toggle="tooltip" data-bs-placement="top" title="Harap isi dengan Nomor Induk Kependudukan Anda" required>
                <small class="text-muted d-block mt-1" id="nikHint">
                    <i class="fa fa-info-circle"></i> <span id="nikCounter">0</span>/16 digit
                </small>
            </div>

            <div class="mb-3">
                <label for="nama_ibu" class="form-label">Nama Ibu Kandung</label>
                <input type="text" class="form-control" id="nama_ibu" name="nama_ibu" placeholder="Masukkan Nama Ibu Kandung"data-bs-toggle="tooltip" data-bs-placement="top" title="Harap isi dengan nama Ibu Kandung anda" required>
            </div>

            <div class="mb-3">
                <label for="kewarganegaraan" class="form-label">Pilih Kewarganegaraan</label>
                <select class="form-select" id="kewarganegaraan" name="kewarganegaraan" data-bs-toggle="tooltip" data-bs-placement="top" title="Harap isi dengan kewarganegaraan anda" required>
                    <option selected value="">Pilih...</option>
                    <option value="WNI">WNI</option>
                    <option value="WNA">WNA</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="no_hp" class="form-label">No Handphone /WA</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="Masukkan No Handphone / WA" data-bs-toggle="tooltip" data-bs-placement="top" title="Harap isi dengan no telepon aktif yang terdaftar pada aplikasi WhatsApp" required>
                <small class="text-muted d-block mt-1" id="noHpHint">
                    <i class="fa fa-info-circle"></i> <span id="noHpCounter">0</span>/15 digit (min: 10)
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

            <!-- CAPTCHA Section -->
            <div class="mb-3">
                <label class="form-label">CAPTCHA - Silakan jawab pertanyaan berikut</label>
                <div class="d-flex align-items-center gap-3">
                    <div id="captchaQuestion" class="px-4 py-3 bg-light border rounded text-center" style="min-width: 120px;">
                        <span class="fw-bold fs-5 text-dark">{{ session('captcha_doktoral_num1', 0) }} + {{ session('captcha_doktoral_num2', 0) }}</span>
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
                ageError.style.display = 'none';
                ageSuccess.style.display = 'none';
                dateInput.classList.remove('is-invalid', 'is-valid');
                if (submitBtn) submitBtn.disabled = false;
                return true;
            }

            const birthDate = new Date(selectedDateStr);
            const today = new Date();
            
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            if (maxYearHint) {
                maxYearHint.textContent = maxAllowedYear;
            }

            dateInput.classList.remove('is-invalid', 'is-valid');
            ageError.style.display = 'none';
            ageSuccess.style.display = 'none';

            if (age < 15) {
                dateInput.classList.add('is-invalid');
                ageError.style.display = 'block';
                if (submitBtn) submitBtn.disabled = true;
                return false;
            } else {
                if (calculatedAge) {
                    calculatedAge.textContent = age;
                }
                dateInput.classList.add('is-valid');
                ageSuccess.style.display = 'block';
                if (submitBtn) submitBtn.disabled = false;
                return true;
            }
        }

        if (dateInput) {
            dateInput.addEventListener('change', validateAge);
            dateInput.addEventListener('input', validateAge);
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
                fetch('{{ route("doktoral.captcha.refresh") }}', {
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
        // NIK INPUT - COUNTER & MODAL
        // ============================================
        const nikInput = document.getElementById('nik');
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
        // FORM SUBMIT & PASSWORD VALIDATION
        // ============================================
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!validateAge()) {
                    e.preventDefault();
                    dateInput.focus();
                    return false;
                }
            });
        }

        // Password match validation
        const passwordConfirm = document.getElementById('password_confirmation');
        if (passwordConfirm) {
            passwordConfirm.addEventListener('input', function() {
                const password = document.getElementById('password').value;
                const confirmation = this.value;
                
                if (password !== confirmation) {
                    this.setCustomValidity('Password tidak cocok');
                } else {
                    this.setCustomValidity('');
                }
            });
        }
    });
</script>
@endpush
