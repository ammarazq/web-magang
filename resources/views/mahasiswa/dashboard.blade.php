<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa - {{ $mahasiswa->nama_lengkap }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .card-status {
            border-left: 4px solid;
        }
        .status-belum_lengkap { border-left-color: #ffc107; }
        .status-lengkap { border-left-color: #17a2b8; }
        .status-diverifikasi { border-left-color: #28a745; }
        .status-ditolak { border-left-color: #dc3545; }
        .doc-item {
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 8px;
            background: #f8f9fa;
        }
        .doc-uploaded {
            background: #d4edda;
            border-left: 4px solid #28a745;
        }
        .doc-missing {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Portal Mahasiswa</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    <i class="fas fa-user"></i> {{ Auth::user()->name }}
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

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-user-circle fa-5x text-primary mb-3"></i>
                        <h5>{{ $mahasiswa->nama_lengkap }}</h5>
                        <p class="text-muted mb-1">{{ $mahasiswa->email }}</p>
                        <p class="text-muted mb-1">{{ $mahasiswa->jenjang }} - {{ $mahasiswa->program_studi }}</p>
                        <p class="badge bg-info">{{ $mahasiswa->jalur_program }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card card-status status-{{ $dokumen->status_dokumen }} mb-4">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-file-alt"></i> Status Dokumen
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Status:</strong> 
                                    @if($dokumen->status_dokumen === 'belum_lengkap')
                                        <span class="badge bg-warning">Belum Lengkap</span>
                                    @elseif($dokumen->status_dokumen === 'lengkap')
                                        <span class="badge bg-info">Lengkap - Menunggu Verifikasi</span>
                                    @elseif($dokumen->status_dokumen === 'diverifikasi')
                                        <span class="badge bg-success">Diverifikasi</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </p>
                                <p class="mb-1"><strong>Kelengkapan:</strong> {{ $dokumen->getPersentaseKelengkapan() }}%</p>
                            </div>
                            <div class="col-md-6">
                                @if($dokumen->verified_by)
                                    <p class="mb-1"><strong>Diverifikasi oleh:</strong> {{ $dokumen->verifiedBy->name }}</p>
                                    <p class="mb-1"><strong>Tanggal:</strong> {{ $dokumen->verified_at->format('d M Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                        
                        @if($dokumen->catatan_verifikasi)
                            <div class="alert alert-{{ $dokumen->status_dokumen === 'diverifikasi' ? 'success' : 'danger' }} mt-3 mb-0">
                                <strong>Catatan Admin:</strong><br>
                                {{ $dokumen->catatan_verifikasi }}
                            </div>
                        @endif

                        <div class="progress mt-3" style="height: 25px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $dokumen->getPersentaseKelengkapan() }}%">
                                {{ $dokumen->getPersentaseKelengkapan() }}%
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dokumen yang sudah diupload -->
                @if(count($uploadedDocs) > 0)
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-check-circle"></i> Dokumen yang Sudah Diupload
                    </div>
                    <div class="card-body">
                        @foreach($uploadedDocs as $field => $name)
                            <div class="doc-item doc-uploaded">
                                <i class="fas fa-file-check text-success"></i>
                                <strong>{{ $name }}</strong>
                                <a href="{{ asset('storage/' . $dokumen->$field) }}" target="_blank" class="btn btn-sm btn-outline-primary float-end">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Form upload untuk dokumen yang belum diupload -->
                @if(count($missingDocs) > 0 && $dokumen->status_dokumen !== 'diverifikasi')
                <div class="card">
                    <div class="card-header bg-warning">
                        <i class="fas fa-upload"></i> Upload Dokumen yang Belum Lengkap
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mahasiswa.upload') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                            @csrf
                            @foreach($missingDocs as $field => $name)
                                <div class="mb-3">
                                    <label class="form-label"><strong>{{ $name }}</strong></label>
                                    @if(in_array($field, ['foto_formal', 'ktp', 'formulir_keabsahan', 'formulir_pendaftaran']))
                                        <input type="file" 
                                               name="{{ $field }}" 
                                               class="form-control @error($field) is-invalid @enderror"
                                               accept=".jpg,.jpeg,.png"
                                               data-max-size="2097152"
                                               onchange="validateFile(this, 2097152, 'image')">
                                        <small class="text-muted">
                                            Format: JPG/PNG, Max: 2MB
                                        </small>
                                    @else
                                        <input type="file" 
                                               name="{{ $field }}" 
                                               class="form-control @error($field) is-invalid @enderror"
                                               accept=".pdf"
                                               data-max-size="5242880"
                                               onchange="validateFile(this, 5242880, 'pdf')">
                                        <small class="text-muted">
                                            Format: PDF, Max: 5MB
                                        </small>
                                    @endif
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback" id="error-{{ $field }}"></div>
                                </div>
                            @endforeach

                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-upload"></i> Upload Dokumen
                            </button>
                        </form>
                    </div>
                </div>
                
                <script>
                    function validateFile(input, maxSize, type) {
                        const file = input.files[0];
                        const errorDiv = document.getElementById('error-' + input.name);
                        const submitBtn = document.getElementById('submitBtn');
                        
                        // Reset error
                        input.classList.remove('is-invalid');
                        errorDiv.textContent = '';
                        errorDiv.style.display = 'none';
                        
                        if (file) {
                            // Validasi ukuran file
                            if (file.size > maxSize) {
                                const maxSizeMB = maxSize / (1024 * 1024);
                                input.classList.add('is-invalid');
                                errorDiv.textContent = `Ukuran file terlalu besar! Maksimal ${maxSizeMB}MB`;
                                errorDiv.style.display = 'block';
                                input.value = '';
                                return false;
                            }
                            
                            // Validasi tipe file
                            const fileName = file.name.toLowerCase();
                            if (type === 'image') {
                                if (!fileName.match(/\.(jpg|jpeg|png)$/)) {
                                    input.classList.add('is-invalid');
                                    errorDiv.textContent = 'Format file harus JPG, JPEG, atau PNG!';
                                    errorDiv.style.display = 'block';
                                    input.value = '';
                                    return false;
                                }
                            } else if (type === 'pdf') {
                                if (!fileName.endsWith('.pdf')) {
                                    input.classList.add('is-invalid');
                                    errorDiv.textContent = 'Format file harus PDF!';
                                    errorDiv.style.display = 'block';
                                    input.value = '';
                                    return false;
                                }
                            }
                            
                            // Tampilkan info ukuran file
                            const fileSizeKB = (file.size / 1024).toFixed(2);
                            const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                            const sizeInfo = fileSizeKB < 1024 ? fileSizeKB + ' KB' : fileSizeMB + ' MB';
                            input.nextElementSibling.innerHTML = `<span class="text-success"><i class="fas fa-check"></i> File dipilih: ${file.name} (${sizeInfo})</span>`;
                        }
                        
                        return true;
                    }
                    
                    // Validasi sebelum submit
                    document.getElementById('uploadForm').addEventListener('submit', function(e) {
                        const fileInputs = this.querySelectorAll('input[type="file"]');
                        let hasFile = false;
                        let hasError = false;
                        
                        fileInputs.forEach(function(input) {
                            if (input.files.length > 0) {
                                hasFile = true;
                            }
                            if (input.classList.contains('is-invalid')) {
                                hasError = true;
                            }
                        });
                        
                        if (!hasFile) {
                            e.preventDefault();
                            alert('Pilih minimal satu dokumen untuk diupload!');
                            return false;
                        }
                        
                        if (hasError) {
                            e.preventDefault();
                            alert('Ada kesalahan pada file yang dipilih. Periksa kembali file Anda!');
                            return false;
                        }
                    });
                </script>
                @elseif($dokumen->status_dokumen === 'diverifikasi')
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <strong>Selamat!</strong> Dokumen Anda sudah diverifikasi dan disetujui oleh admin.
                </div>
                @elseif($dokumen->status_dokumen === 'lengkap')
                <div class="alert alert-info">
                    <i class="fas fa-hourglass-half"></i> Dokumen Anda sudah lengkap dan sedang menunggu verifikasi dari admin.
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
