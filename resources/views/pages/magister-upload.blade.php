<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Dokumen Magister (S2) - {{ $mahasiswa->nama_lengkap }}</title>
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
                <i class="fas fa-graduation-cap"></i> Upload Dokumen Magister (S2)
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
                        <p class="mb-1"><small><strong>Program:</strong></small><br>Magister (S2)</p>
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
            </div>

            <div class="col-md-9">
                <form action="{{ route('magister.upload.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Dokumen Umum -->
                    <div class="upload-section">
                        <h5 class="mb-3">
                            <i class="fas fa-folder"></i> Dokumen Umum
                        </h5>

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
                                <i class="fas fa-info-circle"></i> Format: JPG, Maks: 1MB (Foto 3x4, latar belakang merah/biru)
                                @if($dokumen && $dokumen->foto_formal)
                                    <br><strong>File saat ini:</strong> {{ $dokumen->foto_formal }}
                                @endif
                            </div>
                        </div>

                        <div class="upload-item {{ $dokumen && $dokumen->ktp ? 'uploaded' : '' }}">
                            <label class="form-label">
                                <i class="fas fa-id-card"></i> KTP
                                <span class="required-badge">WAJIB</span>
                                @if($dokumen && $dokumen->ktp)
                                    <span class="uploaded-badge"><i class="fas fa-check"></i> Sudah Diupload</span>
                                @endif
                            </label>
                            <input type="file" class="form-control" name="ktp" accept=".jpg,.jpeg">
                            <div class="file-info mt-1">
                                <i class="fas fa-info-circle"></i> Format: JPG, Maks: 1MB
                                @if($dokumen->ktp)
                                    <br><strong>File saat ini:</strong> {{ $dokumen->ktp }}
                                @endif
                            </div>
                        </div>

                        <div class="upload-item {{ $dokumen->ijazah_slta ? 'uploaded' : '' }}">
                            <label class="form-label">
                                <i class="fas fa-certificate"></i> FC Ijazah SLTA (Legalisir)
                                <span class="required-badge">WAJIB</span>
                                @if($dokumen->ijazah_slta)
                                    <span class="uploaded-badge"><i class="fas fa-check"></i> Sudah Diupload</span>
                                @endif
                            </label>
                            <input type="file" class="form-control" name="ijazah_slta" accept=".pdf">
                            <div class="file-info mt-1">
                                <i class="fas fa-info-circle"></i> Format: PDF, Maks: 2MB
                                @if($dokumen->ijazah_slta)
                                    <br><strong>File saat ini:</strong> {{ $dokumen->ijazah_slta }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Dokumen Khusus S2 -->
                    <div class="upload-section">
                        <h5 class="mb-3">
                            <i class="fas fa-folder-open"></i> Dokumen Khusus Program Magister (S2)
                        </h5>

                        <div class="upload-item {{ $dokumen->sertifikat_akreditasi_prodi ? 'uploaded' : '' }}">
                            <label class="form-label">
                                <i class="fas fa-award"></i> Sertifikat Akreditasi Prodi D4/S1
                                <span class="required-badge">WAJIB</span>
                                @if($dokumen->sertifikat_akreditasi_prodi)
                                    <span class="uploaded-badge"><i class="fas fa-check"></i> Sudah Diupload</span>
                                @endif
                            </label>
                            <input type="file" class="form-control" name="sertifikat_akreditasi_prodi" accept=".pdf">
                            <div class="file-info mt-1">
                                <i class="fas fa-info-circle"></i> Format: PDF, Maks: 2MB
                                @if($dokumen->sertifikat_akreditasi_prodi)
                                    <br><strong>File saat ini:</strong> {{ $dokumen->sertifikat_akreditasi_prodi }}
                                @endif
                            </div>
                        </div>

                        <div class="upload-item {{ $dokumen && $dokumen->transkrip_d3_d4_s1 ? 'uploaded' : '' }}">
                            <label class="form-label">
                                <i class="fas fa-file-alt"></i> FC Ijazah dan Transkrip Nilai D4/S1 (Legalisir)
                                <span class="required-badge">WAJIB</span>
                                @if($dokumen && $dokumen->transkrip_d3_d4_s1)
                                    <span class="uploaded-badge"><i class="fas fa-check"></i> Sudah Diupload</span>
                                @endif
                            </label>
                            <input type="file" class="form-control" name="transkrip_d3_d4_s1" accept=".pdf">
                            <div class="file-info mt-1">
                                <i class="fas fa-info-circle"></i> Format: PDF, Maks: 2MB (Gabungkan Ijazah dan Transkrip dalam 1 file)
                                @if($dokumen && $dokumen->transkrip_d3_d4_s1)
                                    <br><strong>File saat ini:</strong> {{ $dokumen->transkrip_d3_d4_s1 }}
                                @endif
                            </div>
                        </div>

                        <div class="upload-item {{ $dokumen && $dokumen->sertifikat_toefl ? 'uploaded' : '' }}">
                            <label class="form-label">
                                <i class="fas fa-language"></i> Sertifikat TOEFL min. 450
                                <span class="required-badge">WAJIB</span>
                                @if($dokumen && $dokumen->sertifikat_toefl)
                                    <span class="uploaded-badge"><i class="fas fa-check"></i> Sudah Diupload</span>
                                @endif
                            </label>
                            <input type="file" class="form-control" name="sertifikat_toefl" accept=".pdf">
                            <div class="file-info mt-1">
                                <i class="fas fa-info-circle"></i> Format: PDF, Maks: 2MB (maksimal 2 tahun terakhir)
                                @if($dokumen && $dokumen->sertifikat_toefl)
                                    <br><strong>File saat ini:</strong> {{ $dokumen->sertifikat_toefl }}
                                @endif
                            </div>
                        </div>

                        <div class="upload-item {{ $dokumen->rancangan_penelitian ? 'uploaded' : '' }}">
                            <label class="form-label">
                                <i class="fas fa-flask"></i> Rancangan Penelitian Singkat
                                <span class="required-badge">WAJIB</span>
                                @if($dokumen->rancangan_penelitian)
                                    <span class="uploaded-badge"><i class="fas fa-check"></i> Sudah Diupload</span>
                                @endif
                            </label>
                            <input type="file" class="form-control" name="rancangan_penelitian" accept=".pdf">
                            <div class="file-info mt-1">
                                <i class="fas fa-info-circle"></i> Format: PDF, Maks: 2MB
                                @if($dokumen->rancangan_penelitian)
                                    <br><strong>File saat ini:</strong> {{ $dokumen->rancangan_penelitian }}
                                @endif
                            </div>
                        </div>

                        <div class="upload-item {{ $dokumen && $dokumen->sk_mampu_komputer ? 'uploaded' : '' }}">
                            <label class="form-label">
                                <i class="fas fa-laptop"></i> SK Mampu Menggunakan Komputer
                                <span class="required-badge">WAJIB</span>
                                @if($dokumen && $dokumen->sk_mampu_komputer)
                                    <span class="uploaded-badge"><i class="fas fa-check"></i> Sudah Diupload</span>
                                @endif
                            </label>
                            <input type="file" class="form-control" name="sk_mampu_komputer" accept=".pdf">
                            <div class="file-info mt-1">
                                <i class="fas fa-info-circle"></i> Format: PDF, Maks: 2MB
                                @if($dokumen && $dokumen->sk_mampu_komputer)
                                    <br><strong>File saat ini:</strong> {{ $dokumen->sk_mampu_komputer }}
                                @endif
                            </div>
                        </div>

                        <div class="upload-item {{ $dokumen && $dokumen->bukti_tes_tpa ? 'uploaded' : '' }}">
                            <label class="form-label">
                                <i class="fas fa-clipboard-check"></i> Bukti Tes Potensi Akademik (TPA)
                                <span class="required-badge">WAJIB</span>
                                @if($dokumen && $dokumen->bukti_tes_tpa)
                                    <span class="uploaded-badge"><i class="fas fa-check"></i> Sudah Diupload</span>
                                @endif
                            </label>
                            <input type="file" class="form-control" name="bukti_tes_tpa" accept=".pdf">
                            <div class="file-info mt-1">
                                <i class="fas fa-info-circle"></i> Format: PDF, Maks: 2MB
                                @if($dokumen && $dokumen->bukti_tes_tpa)
                                    <br><strong>File saat ini:</strong> {{ $dokumen->bukti_tes_tpa }}
                                @endif
                            </div>
                        </div>

                        <div class="upload-item {{ $dokumen && $dokumen->seleksi_tes_substansi ? 'uploaded' : '' }}">
                            <label class="form-label">
                                <i class="fas fa-edit"></i> Bukti Seleksi Tes Substansi
                                <span class="required-badge">WAJIB</span>
                                @if($dokumen && $dokumen->seleksi_tes_substansi)
                                    <span class="uploaded-badge"><i class="fas fa-check"></i> Sudah Diupload</span>
                                @endif
                            </label>
                            <input type="file" class="form-control" name="seleksi_tes_substansi" accept=".pdf">
                            <div class="file-info mt-1">
                                <i class="fas fa-info-circle"></i> Format: PDF, Maks: 2MB
                                @if($dokumen && $dokumen->seleksi_tes_substansi)
                                    <br><strong>File saat ini:</strong> {{ $dokumen->seleksi_tes_substansi }}
                                @endif
                            </div>
                        </div>

                        <div class="upload-item {{ $dokumen && $dokumen->formulir_isian_foto ? 'uploaded' : '' }}">
                            <label class="form-label">
                                <i class="fas fa-file-image"></i> Formulir Isian Foto
                                <span class="required-badge">WAJIB</span>
                                @if($dokumen && $dokumen->formulir_isian_foto)
                                    <span class="uploaded-badge"><i class="fas fa-check"></i> Sudah Diupload</span>
                                @endif
                            </label>
                            <input type="file" class="form-control" name="formulir_isian_foto" accept=".jpg,.jpeg,.pdf">
                            <div class="file-info mt-1">
                                <i class="fas fa-info-circle"></i> Format: JPG/PDF, Maks: 2MB
                                @if($dokumen && $dokumen->formulir_isian_foto)
                                    <br><strong>File saat ini:</strong> {{ $dokumen->formulir_isian_foto }}
                                @endif
                            </div>
                        </div>

                        <div class="upload-item {{ $dokumen && $dokumen->riwayat_hidup ? 'uploaded' : '' }}">
                            <label class="form-label">
                                <i class="fas fa-user-circle"></i> Daftar Riwayat Hidup
                                <span class="required-badge">WAJIB</span>
                                @if($dokumen && $dokumen->riwayat_hidup)
                                    <span class="uploaded-badge"><i class="fas fa-check"></i> Sudah Diupload</span>
                                @endif
                            </label>
                            <input type="file" class="form-control" name="riwayat_hidup" accept=".pdf">
                            <div class="file-info mt-1">
                                <i class="fas fa-info-circle"></i> Format: PDF, Maks: 2MB
                                @if($dokumen && $dokumen->riwayat_hidup)
                                    <br><strong>File saat ini:</strong> {{ $dokumen->riwayat_hidup }}
                                @endif
                            </div>
                        </div>
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
        // Validasi ukuran file saat dipilih
        document.addEventListener('DOMContentLoaded', function() {
            const fileInputs = document.querySelectorAll('input[type="file"]');
            
            fileInputs.forEach(input => {
                input.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    
                    const fileName = file.name;
                    const fileSize = file.size;
                    const fileExtension = fileName.split('.').pop().toLowerCase();
                    
                    let maxSize;
                    let maxSizeText;
                    
                    if (['jpg', 'jpeg', 'png'].includes(fileExtension)) {
                        maxSize = 2 * 1024 * 1024;
                        maxSizeText = '2MB';
                    } else if (fileExtension === 'pdf') {
                        maxSize = 5 * 1024 * 1024;
                        maxSizeText = '5MB';
                    } else {
                        alert('Format file tidak didukung!');
                        e.target.value = '';
                        return;
                    }
                    
                    if (fileSize > maxSize) {
                        const fileSizeMB = (fileSize / (1024 * 1024)).toFixed(2);
                        alert(`Ukuran file terlalu besar (${fileSizeMB}MB)!\nMaksimal ukuran file adalah ${maxSizeText}.\nSilakan kompres atau pilih file lain.`);
                        e.target.value = '';
                        return;
                    }
                });
            });
        });
    </script>
</body>
</html>

