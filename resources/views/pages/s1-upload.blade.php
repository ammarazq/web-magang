<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Dokumen S1 - {{ $mahasiswa->nama_lengkap }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .upload-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .upload-item {
            background: white;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        .upload-item:hover {
            border-color: #0d6efd;
            background: #f0f8ff;
        }
        .upload-item.uploaded {
            border-color: #28a745;
            border-style: solid;
            background: #d4edda;
        }
        .file-info {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .required-badge {
            background: #dc3545;
            color: white;
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 3px;
            margin-left: 5px;
        }
        .uploaded-badge {
            background: #28a745;
            color: white;
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 3px;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap"></i> Upload Dokumen Sarjana (S1) - {{ $mahasiswa->jalur_program }}
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    <i class="fas fa-user"></i> {{ $mahasiswa->nama_lengkap }}
                </span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show">
                <i class="fas fa-info-circle"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong><i class="fas fa-exclamation-triangle"></i> Terdapat kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-info-circle"></i> Informasi Mahasiswa</h6>
                        <hr>
                        <p class="mb-1"><small><strong>Nama:</strong></small><br>{{ $mahasiswa->nama_lengkap }}</p>
                        <p class="mb-1"><small><strong>Email:</strong></small><br>{{ $mahasiswa->email }}</p>
                        <p class="mb-1"><small><strong>Program:</strong></small><br>Sarjana (S1)</p>
                        <p class="mb-1"><small><strong>Jalur:</strong></small><br>{{ $mahasiswa->jalur_program }}</p>
                        <p class="mb-1"><small><strong>Prodi:</strong></small><br>{{ $mahasiswa->getNamaProgramStudi() }}</p>
                        <p class="mb-0"><small><strong>Status Dokumen:</strong></small><br>
                            @if($dokumen && $dokumen->status_dokumen === 'belum_lengkap')
                                <span class="badge bg-warning">Belum Lengkap</span>
                            @elseif($dokumen && $dokumen->status_dokumen === 'lengkap')
                                <span class="badge bg-info">Lengkap</span>
                            @elseif($dokumen && $dokumen->status_dokumen === 'diverifikasi')
                                <span class="badge bg-success">Diverifikasi</span>
                            @elseif($dokumen && $dokumen->status_dokumen === 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @else
                                <span class="badge bg-secondary">Belum Ada</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-list-check"></i> Kelengkapan Dokumen</h6>
                        <hr>
                        <div class="progress mb-2" style="height: 25px;">
                            @php
                                $total = $mahasiswa->jalur_program === 'RPL' ? 6 : 5;
                                $uploaded = 0;
                                if($dokumen) {
                                    if($dokumen->formulir_pendaftaran) $uploaded++;
                                    if($dokumen->formulir_keabsahan) $uploaded++;
                                    if($dokumen->foto_formal) $uploaded++;
                                    if($dokumen->ktp) $uploaded++;
                                    if($mahasiswa->jalur_program === 'RPL') {
                                        if($dokumen->ijazah_slta_asli) $uploaded++;
                                        if($dokumen->transkrip_nilai) $uploaded++;
                                        // ijazah_d3_d4_s1 opsional, tidak dihitung di required
                                        $total = 6; // 6 dokumen wajib untuk RPL
                                    } else {
                                        if($dokumen->ijazah_slta) $uploaded++;
                                    }
                                }
                                $percentage = $total > 0 ? ($uploaded / $total) * 100 : 0;
                            @endphp
                            <div class="progress-bar {{ $percentage == 100 ? 'bg-success' : 'bg-warning' }}" 
                                 role="progressbar" 
                                 style="width: {{ $percentage }}%;" 
                                 aria-valuenow="{{ $percentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ $uploaded }}/{{ $total }}
                            </div>
                        </div>
                        <small class="text-muted">{{ round($percentage) }}% dokumen terupload</small>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <form action="{{ route('sarjana.upload.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Dokumen Umum -->
                    <div class="upload-section">
                        <h5 class="mb-3">
                            <i class="fas fa-folder"></i> Dokumen Persyaratan S1 {{ $mahasiswa->jalur_program }}
                        </h5>
                        <p class="text-muted mb-3">
                            <i class="fas fa-info-circle"></i> Upload dokumen persyaratan untuk program Sarjana (S1) jalur {{ $mahasiswa->jalur_program }}. 
                            Semua dokumen bertanda <span class="required-badge">WAJIB</span> harus diupload.
                        </p>

                        <div class="upload-item {{ $dokumen && $dokumen->formulir_pendaftaran ? 'uploaded' : '' }}">
                            <label class="form-label">
                                <i class="fas fa-file-alt"></i> Formulir Pendaftaran
                                <span class="required-badge">WAJIB</span>
                                @if($dokumen && $dokumen->formulir_pendaftaran)
                                    <span class="uploaded-badge"><i class="fas fa-check"></i> Sudah Diupload</span>
                                @endif
                            </label>
                            <input type="file" class="form-control" name="formulir_pendaftaran" accept=".jpg,.jpeg,.pdf">
                            <div class="file-info mt-1">
                                <i class="fas fa-info-circle"></i> Format: JPG/PDF, Maks: 2MB
                                @if($dokumen && $dokumen->formulir_pendaftaran)
                                    <br><strong>File saat ini:</strong> {{ $dokumen->formulir_pendaftaran }}
                                @endif
                            </div>
                        </div>

                        <div class="upload-item {{ $dokumen && $dokumen->formulir_keabsahan ? 'uploaded' : '' }}">
                            <label class="form-label">
                                <i class="fas fa-file-alt"></i> Formulir Keabsahan Dokumen
                                <span class="required-badge">WAJIB</span>
                                @if($dokumen && $dokumen->formulir_keabsahan)
                                    <span class="uploaded-badge"><i class="fas fa-check"></i> Sudah Diupload</span>
                                @endif
                            </label>
                            <input type="file" class="form-control" name="formulir_keabsahan" accept=".jpg,.jpeg,.pdf">
                            <div class="file-info mt-1">
                                <i class="fas fa-info-circle"></i> Format: JPG/PDF, Maks: 2MB
                                @if($dokumen && $dokumen->formulir_keabsahan)
                                    <br><strong>File saat ini:</strong> {{ $dokumen->formulir_keabsahan }}
                                @endif
                            </div>
                        </div>

                        <div class="upload-item {{ $dokumen && $dokumen->foto_formal ? 'uploaded' : '' }}">
                            <label class="form-label">
                                <i class="fas fa-image"></i> Foto Formal
                                <span class="required-badge">WAJIB</span>
                                @if($dokumen && $dokumen->foto_formal)
                                    <span class="uploaded-badge"><i class="fas fa-check"></i> Sudah Diupload</span>
                                @endif
                            </label>
                            <input type="file" class="form-control" name="foto_formal" accept=".jpg,.jpeg">
                            <div class="file-info mt-1">
                                <i class="fas fa-info-circle"></i> Format: JPG, Maks: 1MB (Foto 3x4, latar belakang merah/biru, berpakaian formal)
                                @if($dokumen && $dokumen->foto_formal)
                                    <br><strong>File saat ini:</strong> {{ $dokumen->foto_formal }}
                                @endif
                            </div>
                        </div>

                        <div class="upload-item {{ $dokumen && $dokumen->ktp ? 'uploaded' : '' }}">
                            <label class="form-label">
                                <i class="fas fa-id-card"></i> KTP / Identitas
                                <span class="required-badge">WAJIB</span>
                                @if($dokumen && $dokumen->ktp)
                                    <span class="uploaded-badge"><i class="fas fa-check"></i> Sudah Diupload</span>
                                @endif
                            </label>
                            <input type="file" class="form-control" name="ktp" accept=".jpg,.jpeg">
                            <div class="file-info mt-1">
                                <i class="fas fa-info-circle"></i> Format: JPG, Maks: 1MB (Scan KTP yang masih berlaku)
                                @if($dokumen && $dokumen->ktp)
                                    <br><strong>File saat ini:</strong> {{ $dokumen->ktp }}
                                @endif
                            </div>
                        </div>

                        @if($mahasiswa->jalur_program === 'Non RPL')
                            <div class="upload-item {{ $dokumen && $dokumen->ijazah_slta ? 'uploaded' : '' }}">
                                <label class="form-label">
                                    <i class="fas fa-certificate"></i> FC Ijazah SLTA / Sederajat (Legalisir)
                                    <span class="required-badge">WAJIB</span>
                                    @if($dokumen && $dokumen->ijazah_slta)
                                        <span class="uploaded-badge"><i class="fas fa-check"></i> Sudah Diupload</span>
                                    @endif
                                </label>
                                <input type="file" class="form-control" name="ijazah_slta" accept=".pdf">
                                <div class="file-info mt-1">
                                    <i class="fas fa-info-circle"></i> Format: PDF, Maks: 2MB (Fotocopy ijazah SMA/SMK/MA yang sudah dilegalisir)
                                    @if($dokumen && $dokumen->ijazah_slta)
                                        <br><strong>File saat ini:</strong> {{ $dokumen->ijazah_slta }}
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($mahasiswa->jalur_program === 'RPL')
                        <!-- Dokumen Khusus RPL -->
                        <div class="upload-section">
                            <h5 class="mb-3">
                                <i class="fas fa-folder-open"></i> Dokumen Khusus RPL (Rekognisi Pembelajaran Lampau)
                            </h5>
                            <p class="text-muted mb-3">
                                <i class="fas fa-info-circle"></i> Dokumen tambahan untuk jalur RPL
                            </p>

                            <div class="upload-item {{ $dokumen && $dokumen->ijazah_slta_asli ? 'uploaded' : '' }}">
                                <label class="form-label">
                                    <i class="fas fa-certificate"></i> Ijazah SLTA Asli
                                    <span class="required-badge">WAJIB</span>
                                    @if($dokumen && $dokumen->ijazah_slta_asli)
                                        <span class="uploaded-badge"><i class="fas fa-check"></i> Sudah Diupload</span>
                                    @endif
                                </label>
                                <input type="file" class="form-control" name="ijazah_slta_asli" accept=".pdf">
                                <div class="file-info mt-1">
                                    <i class="fas fa-info-circle"></i> Format: PDF, Maks: 2MB
                                    @if($dokumen && $dokumen->ijazah_slta_asli)
                                        <br><strong>File saat ini:</strong> {{ $dokumen->ijazah_slta_asli }}
                                    @endif
                                </div>
                            </div>

                            <div class="upload-item {{ $dokumen && $dokumen->transkrip_nilai ? 'uploaded' : '' }}">
                                <label class="form-label">
                                    <i class="fas fa-file-alt"></i> Transkrip Nilai D3/D4/S1
                                    <span class="required-badge">WAJIB</span>
                                    @if($dokumen && $dokumen->transkrip_nilai)
                                        <span class="uploaded-badge"><i class="fas fa-check"></i> Sudah Diupload</span>
                                    @endif
                                </label>
                                <input type="file" class="form-control" name="transkrip_nilai" accept=".pdf">
                                <div class="file-info mt-1">
                                    <i class="fas fa-info-circle"></i> Format: PDF, Maks: 2MB
                                    @if($dokumen && $dokumen->transkrip_nilai)
                                        <br><strong>File saat ini:</strong> {{ $dokumen->transkrip_nilai }}
                                    @endif
                                </div>
                            </div>

                            <div class="upload-item {{ $dokumen && $dokumen->ijazah_d3_d4_s1 ? 'uploaded' : '' }}">
                                <label class="form-label">
                                    <i class="fas fa-certificate"></i> FC Ijazah D3/D4/S1 (Legalisir) - <span style="color: #6c757d;">(jika sudah lulus)</span>
                                    <span class="uploaded-badge" style="background: #6c757d;">OPSIONAL</span>
                                    @if($dokumen && $dokumen->ijazah_d3_d4_s1)
                                        <span class="uploaded-badge"><i class="fas fa-check"></i> Sudah Diupload</span>
                                    @endif
                                </label>
                                <input type="file" class="form-control" name="ijazah_d3_d4_s1" accept=".pdf">
                                <div class="file-info mt-1">
                                    <i class="fas fa-info-circle"></i> Format: PDF, Maks: 2MB
                                    @if($dokumen && $dokumen->ijazah_d3_d4_s1)
                                        <br><strong>File saat ini:</strong> {{ $dokumen->ijazah_d3_d4_s1 }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb"></i> <strong>Catatan Penting:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Pastikan semua dokumen terlihat jelas dan mudah dibaca</li>
                            <li>File yang sudah diupload dapat diganti dengan upload ulang</li>
                            <li>Dokumen akan diverifikasi oleh admin setelah lengkap</li>
                            <li>Anda dapat mengupload dokumen secara bertahap</li>
                            @if($mahasiswa->jalur_program === 'RPL')
                                <li>Jalur RPL memerlukan dokumen tambahan dari pendidikan sebelumnya</li>
                            @endif
                        </ul>
                    </div>

                    <div class="d-grid gap-2 mb-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-cloud-upload-alt"></i> Upload Dokumen
                        </button>
                        <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tracking file yang sudah dipilih untuk deteksi duplikat
        const selectedFiles = new Map();

        // Validasi ukuran file dan deteksi duplikat
        document.addEventListener('DOMContentLoaded', function() {
            const fileInputs = document.querySelectorAll('input[type="file"]');
            
            fileInputs.forEach(input => {
                input.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    
                    const fileName = file.name;
                    const fileSize = file.size;
                    const fileExtension = fileName.split('.').pop().toLowerCase();
                    const inputName = e.target.name;
                    
                    // 1. VALIDASI FORMAT FILE
                    let maxSize;
                    let maxSizeText;
                    
                    if (inputName === 'foto_formal' || inputName === 'ktp') {
                        maxSize = 1 * 1024 * 1024; // 1MB untuk foto
                        maxSizeText = '1MB';
                    } else if (['jpg', 'jpeg', 'png'].includes(fileExtension)) {
                        maxSize = 2 * 1024 * 1024; // 2MB untuk gambar lain
                        maxSizeText = '2MB';
                    } else if (fileExtension === 'pdf') {
                        maxSize = 2 * 1024 * 1024; // 2MB untuk PDF
                        maxSizeText = '2MB';
                    } else {
                        showError('Format file tidak didukung! Harap upload file dengan format yang sesuai.');
                        e.target.value = '';
                        return;
                    }
                    
                    // 2. VALIDASI UKURAN FILE
                    if (fileSize > maxSize) {
                        const fileSizeMB = (fileSize / (1024 * 1024)).toFixed(2);
                        const maxSizeMB = (maxSize / (1024 * 1024)).toFixed(0);
                        showError(
                            `⚠️ UKURAN FILE TERLALU BESAR!\n\n` +
                            `Ukuran file: ${fileSizeMB} MB\n` +
                            `Maksimal: ${maxSizeMB} MB\n\n` +
                            `Silakan kompres file atau pilih file lain yang lebih kecil.`
                        );
                        e.target.value = '';
                        
                        const uploadItem = e.target.closest('.upload-item');
                        if (uploadItem) {
                            uploadItem.style.borderColor = '#dc3545';
                            uploadItem.style.backgroundColor = '#f8d7da';
                            setTimeout(() => {
                                uploadItem.style.borderColor = '';
                                uploadItem.style.backgroundColor = '';
                            }, 3000);
                        }
                        return;
                    }
                    
                    // 3. DETEKSI FILE DUPLIKAT
                    let isDuplicate = false;
                    let duplicateField = '';
                    
                    selectedFiles.forEach((existingFile, field) => {
                        if (field !== inputName && existingFile.name === fileName && existingFile.size === fileSize) {
                            isDuplicate = true;
                            duplicateField = field;
                        }
                    });
                    
                    if (isDuplicate) {
                        showError(
                            `⚠️ FILE DUPLIKAT TERDETEKSI!\n\n` +
                            `File "${fileName}" sudah dipilih untuk field lain.\n\n` +
                            `Pastikan Anda tidak salah memilih file yang sama untuk dokumen yang berbeda!`
                        );
                        
                        const confirmUpload = confirm(
                            `Apakah Anda yakin file ini berbeda dengan yang sudah dipilih?\n\n` +
                            `Klik OK untuk tetap upload, atau Cancel untuk memilih file lain.`
                        );
                        
                        if (!confirmUpload) {
                            e.target.value = '';
                            return;
                        }
                    }
                    
                    // 4. SIMPAN FILE KE TRACKING
                    selectedFiles.set(inputName, { name: fileName, size: fileSize });
                    
                    // 5. TAMPILKAN KONFIRMASI SUKSES
                    const uploadItem = e.target.closest('.upload-item');
                    if (uploadItem) {
                        uploadItem.style.borderColor = '#28a745';
                        uploadItem.style.backgroundColor = '#d4edda';
                    }
                    
                    const fileSizeKB = (fileSize / 1024).toFixed(0);
                    showSuccess(`✓ File dipilih: ${fileName} (${fileSizeKB} KB)`);
                });
            });
            
            // Validasi sebelum submit form
            const uploadForm = document.querySelector('form');
            if (uploadForm) {
                uploadForm.addEventListener('submit', function(e) {
                    const fileInputs = this.querySelectorAll('input[type="file"]');
                    let hasFile = false;
                    
                    fileInputs.forEach(input => {
                        if (input.files.length > 0) hasFile = true;
                    });
                    
                    if (!hasFile) {
                        e.preventDefault();
                        alert('Harap pilih minimal satu file untuk diupload!');
                        return false;
                    }
                });
            }
        });
        
        function showError(message) {
            alert(message);
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
            alertDiv.innerHTML = `
                <i class="fas fa-exclamation-triangle"></i> <strong>Error:</strong><br>
                ${message.replace(/\n/g, '<br>')}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector('.container').insertBefore(alertDiv, document.querySelector('.container').firstChild);
            setTimeout(() => alertDiv.remove(), 5000);
        }
        
        function showSuccess(message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
            alertDiv.innerHTML = `<i class="fas fa-check-circle"></i> ${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
            document.querySelector('.container').insertBefore(alertDiv, document.querySelector('.container').firstChild);
            setTimeout(() => alertDiv.remove(), 3000);
        }
    </script>
</body>
</html>
