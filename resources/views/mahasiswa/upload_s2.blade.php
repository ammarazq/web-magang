<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Dokumen S2 (Magister) - {{ $mahasiswa->nama_lengkap }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .header-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-bottom: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .header-card h2 {
            margin: 0;
            font-weight: 700;
            font-size: 28px;
        }

        .header-card .subtitle {
            opacity: 0.9;
            margin-top: 5px;
        }

        .upload-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-bottom: 20px;
        }

        .section-title {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid var(--primary-color);
        }

        .document-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid var(--warning-color);
            transition: all 0.3s ease;
        }

        .document-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .document-item.uploaded {
            border-left-color: var(--success-color);
            background: #d4edda;
        }

        .document-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            font-weight: 700;
            margin-right: 15px;
        }

        .document-item.uploaded .document-number {
            background: var(--success-color);
        }

        .document-label {
            font-weight: 600;
            color: #333;
            font-size: 15px;
            margin-bottom: 8px;
        }

        .document-note {
            font-size: 13px;
            color: #dc3545;
            font-style: italic;
            margin-top: 3px;
        }

        .file-input-custom {
            position: relative;
            margin-top: 10px;
        }

        .file-input-custom input[type="file"] {
            border: 2px dashed #ccc;
            border-radius: 8px;
            padding: 12px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .file-input-custom input[type="file"]:hover {
            border-color: var(--primary-color);
            background: #f8f9fa;
        }

        .file-info {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-top: 10px;
        }

        .status-uploaded {
            background: #d4edda;
            color: #155724;
        }

        .status-missing {
            background: #fff3cd;
            color: #856404;
        }

        .btn-upload {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }

        .btn-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
            color: white;
        }

        .progress-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .progress {
            height: 30px;
            border-radius: 15px;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            font-weight: 700;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-alert {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-alert strong {
            color: #1976D2;
        }

        .view-button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }

        .view-button:hover {
            background: var(--secondary-color);
            color: white;
            transform: scale(1.05);
        }

        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .back-button {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
            padding: 8px 20px;
            border-radius: 25px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: white;
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand text-primary fw-bold" href="#">
                <i class="fas fa-graduation-cap"></i> Portal Mahasiswa S2
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-dark me-3">
                    <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                </span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <div class="header-card">
            <a href="{{ route('mahasiswa.dashboard') }}" class="back-button">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
            <h2 class="mt-3"><i class="fas fa-upload"></i> Upload Dokumen S2 (Magister)</h2>
            <p class="subtitle">{{ $mahasiswa->nama_lengkap }} - {{ $mahasiswa->program_studi }}</p>
        </div>

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

        <div class="progress-section">
            <h6 class="mb-3"><strong>Kelengkapan Dokumen</strong></h6>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: {{ $dokumen->getPersentaseKelengkapan() }}%">
                    {{ $dokumen->getPersentaseKelengkapan() }}%
                </div>
            </div>
        </div>

        <div class="upload-card">
            <div class="info-alert">
                <i class="fas fa-info-circle"></i> <strong>Persyaratan Dokumen Program Magister (S2)</strong>
                <p class="mb-0 mt-2">Pastikan semua dokumen yang diupload sesuai dengan format dan ukuran yang ditentukan.</p>
            </div>

            <form action="{{ route('mahasiswa.upload') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf

                <div class="section-title">
                    <i class="fas fa-file-alt"></i> Dokumen Wajib S2
                </div>

                <!-- 1. Formulir Pendaftaran -->
                <div class="document-item {{ $dokumen->formulir_pendaftaran ? 'uploaded' : '' }}">
                    <div class="d-flex align-items-start">
                        <span class="document-number">1</span>
                        <div class="flex-grow-1">
                            <div class="document-label">Formulir Pendaftaran</div>
                            @if($dokumen->formulir_pendaftaran)
                                <span class="status-badge status-uploaded">
                                    <i class="fas fa-check-circle"></i> Sudah Diupload
                                </span>
                                <a href="{{ asset('storage/' . $dokumen->formulir_pendaftaran) }}" target="_blank" class="view-button ms-2">
                                    <i class="fas fa-eye"></i> Lihat File
                                </a>
                            @else
                                <div class="file-input-custom">
                                    <input type="file" name="formulir_pendaftaran" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                    <div class="file-info">
                                        <i class="fas fa-info-circle"></i> Format: JPG/PNG/PDF | Max: 2MB
                                    </div>
                                </div>
                                <span class="status-badge status-missing">
                                    <i class="fas fa-exclamation-circle"></i> Belum Diupload
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 2. Lembar Keabsahan Dokumen -->
                <div class="document-item {{ $dokumen->formulir_keabsahan ? 'uploaded' : '' }}">
                    <div class="d-flex align-items-start">
                        <span class="document-number">2</span>
                        <div class="flex-grow-1">
                            <div class="document-label">Lembar Keabsahan Dokumen</div>
                            @if($dokumen->formulir_keabsahan)
                                <span class="status-badge status-uploaded">
                                    <i class="fas fa-check-circle"></i> Sudah Diupload
                                </span>
                                <a href="{{ asset('storage/' . $dokumen->formulir_keabsahan) }}" target="_blank" class="view-button ms-2">
                                    <i class="fas fa-eye"></i> Lihat File
                                </a>
                            @else
                                <div class="file-input-custom">
                                    <input type="file" name="formulir_keabsahan" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                    <div class="file-info">
                                        <i class="fas fa-info-circle"></i> Format: JPG/PNG/PDF | Max: 2MB
                                    </div>
                                </div>
                                <span class="status-badge status-missing">
                                    <i class="fas fa-exclamation-circle"></i> Belum Diupload
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 3. Scan Foto Formal -->
                <div class="document-item {{ $dokumen->foto_formal ? 'uploaded' : '' }}">
                    <div class="d-flex align-items-start">
                        <span class="document-number">3</span>
                        <div class="flex-grow-1">
                            <div class="document-label">Scan Foto Formal</div>
                            @if($dokumen->foto_formal)
                                <span class="status-badge status-uploaded">
                                    <i class="fas fa-check-circle"></i> Sudah Diupload
                                </span>
                                <a href="{{ asset('storage/' . $dokumen->foto_formal) }}" target="_blank" class="view-button ms-2">
                                    <i class="fas fa-eye"></i> Lihat File
                                </a>
                            @else
                                <div class="file-input-custom">
                                    <input type="file" name="foto_formal" class="form-control" accept=".jpg,.jpeg,.png">
                                    <div class="file-info">
                                        <i class="fas fa-info-circle"></i> Format: JPG/PNG | Max: 2MB
                                    </div>
                                </div>
                                <span class="status-badge status-missing">
                                    <i class="fas fa-exclamation-circle"></i> Belum Diupload
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 4. Scan KTP Asli -->
                <div class="document-item {{ $dokumen->ktp ? 'uploaded' : '' }}">
                    <div class="d-flex align-items-start">
                        <span class="document-number">4</span>
                        <div class="flex-grow-1">
                            <div class="document-label">Scan KTP Asli</div>
                            @if($dokumen->ktp)
                                <span class="status-badge status-uploaded">
                                    <i class="fas fa-check-circle"></i> Sudah Diupload
                                </span>
                                <a href="{{ asset('storage/' . $dokumen->ktp) }}" target="_blank" class="view-button ms-2">
                                    <i class="fas fa-eye"></i> Lihat File
                                </a>
                            @else
                                <div class="file-input-custom">
                                    <input type="file" name="ktp" class="form-control" accept=".jpg,.jpeg,.png">
                                    <div class="file-info">
                                        <i class="fas fa-info-circle"></i> Format: JPG/PNG | Max: 2MB
                                    </div>
                                </div>
                                <span class="status-badge status-missing">
                                    <i class="fas fa-exclamation-circle"></i> Belum Diupload
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 5. FC Ijazah SLTA Legalisir -->
                <div class="document-item {{ $dokumen->ijazah_slta ? 'uploaded' : '' }}">
                    <div class="d-flex align-items-start">
                        <span class="document-number">5</span>
                        <div class="flex-grow-1">
                            <div class="document-label">FC Ijazah SLTA Legalisir</div>
                            @if($dokumen->ijazah_slta)
                                <span class="status-badge status-uploaded">
                                    <i class="fas fa-check-circle"></i> Sudah Diupload
                                </span>
                                <a href="{{ asset('storage/' . $dokumen->ijazah_slta) }}" target="_blank" class="view-button ms-2">
                                    <i class="fas fa-eye"></i> Lihat File
                                </a>
                            @else
                                <div class="file-input-custom">
                                    <input type="file" name="ijazah_slta" class="form-control" accept=".pdf">
                                    <div class="file-info">
                                        <i class="fas fa-info-circle"></i> Format: PDF | Max: 5MB
                                    </div>
                                </div>
                                <span class="status-badge status-missing">
                                    <i class="fas fa-exclamation-circle"></i> Belum Diupload
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 6. Sertifikat Akreditasi Prodi D4/S1 -->
                <div class="document-item {{ $dokumen->sertifikat_akreditasi_prodi ? 'uploaded' : '' }}">
                    <div class="d-flex align-items-start">
                        <span class="document-number">6</span>
                        <div class="flex-grow-1">
                            <div class="document-label">Sertifikat Akreditasi Prodi D4/S1</div>
                            @if($dokumen->sertifikat_akreditasi_prodi)
                                <span class="status-badge status-uploaded">
                                    <i class="fas fa-check-circle"></i> Sudah Diupload
                                </span>
                                <a href="{{ asset('storage/' . $dokumen->sertifikat_akreditasi_prodi) }}" target="_blank" class="view-button ms-2">
                                    <i class="fas fa-eye"></i> Lihat File
                                </a>
                            @else
                                <div class="file-input-custom">
                                    <input type="file" name="sertifikat_akreditasi_prodi" class="form-control" accept=".pdf">
                                    <div class="file-info">
                                        <i class="fas fa-info-circle"></i> Format: PDF | Max: 5MB
                                    </div>
                                </div>
                                <span class="status-badge status-missing">
                                    <i class="fas fa-exclamation-circle"></i> Belum Diupload
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 7. FC Ijazah D4/S1 Legalisir -->
                <div class="document-item {{ $dokumen->ijazah_d3_d4_s1 ? 'uploaded' : '' }}">
                    <div class="d-flex align-items-start">
                        <span class="document-number">7</span>
                        <div class="flex-grow-1">
                            <div class="document-label">FC Ijazah D4/S1 Legalisir</div>
                            @if($dokumen->ijazah_d3_d4_s1)
                                <span class="status-badge status-uploaded">
                                    <i class="fas fa-check-circle"></i> Sudah Diupload
                                </span>
                                <a href="{{ asset('storage/' . $dokumen->ijazah_d3_d4_s1) }}" target="_blank" class="view-button ms-2">
                                    <i class="fas fa-eye"></i> Lihat File
                                </a>
                            @else
                                <div class="file-input-custom">
                                    <input type="file" name="ijazah_d3_d4_s1" class="form-control" accept=".pdf">
                                    <div class="file-info">
                                        <i class="fas fa-info-circle"></i> Format: PDF | Max: 5MB
                                    </div>
                                </div>
                                <span class="status-badge status-missing">
                                    <i class="fas fa-exclamation-circle"></i> Belum Diupload
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 8. FC Transkrip Nilai D4/S1 Legalisir -->
                <div class="document-item {{ $dokumen->transkrip_d3_d4_s1 ? 'uploaded' : '' }}">
                    <div class="d-flex align-items-start">
                        <span class="document-number">8</span>
                        <div class="flex-grow-1">
                            <div class="document-label">FC Transkrip Nilai D4/S1 Legalisir</div>
                            @if($dokumen->transkrip_d3_d4_s1)
                                <span class="status-badge status-uploaded">
                                    <i class="fas fa-check-circle"></i> Sudah Diupload
                                </span>
                                <a href="{{ asset('storage/' . $dokumen->transkrip_d3_d4_s1) }}" target="_blank" class="view-button ms-2">
                                    <i class="fas fa-eye"></i> Lihat File
                                </a>
                            @else
                                <div class="file-input-custom">
                                    <input type="file" name="transkrip_d3_d4_s1" class="form-control" accept=".pdf">
                                    <div class="file-info">
                                        <i class="fas fa-info-circle"></i> Format: PDF | Max: 5MB
                                    </div>
                                </div>
                                <span class="status-badge status-missing">
                                    <i class="fas fa-exclamation-circle"></i> Belum Diupload
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 9. Daftar Riwayat Hidup -->
                <div class="document-item {{ $dokumen->riwayat_hidup ? 'uploaded' : '' }}">
                    <div class="d-flex align-items-start">
                        <span class="document-number">9</span>
                        <div class="flex-grow-1">
                            <div class="document-label">Daftar Riwayat Hidup</div>
                            @if($dokumen->riwayat_hidup)
                                <span class="status-badge status-uploaded">
                                    <i class="fas fa-check-circle"></i> Sudah Diupload
                                </span>
                                <a href="{{ asset('storage/' . $dokumen->riwayat_hidup) }}" target="_blank" class="view-button ms-2">
                                    <i class="fas fa-eye"></i> Lihat File
                                </a>
                            @else
                                <div class="file-input-custom">
                                    <input type="file" name="riwayat_hidup" class="form-control" accept=".pdf">
                                    <div class="file-info">
                                        <i class="fas fa-info-circle"></i> Format: PDF | Max: 5MB
                                    </div>
                                </div>
                                <span class="status-badge status-missing">
                                    <i class="fas fa-exclamation-circle"></i> Belum Diupload
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 10. Sertifikat TOEFL -->
                <div class="document-item {{ $dokumen->sertifikat_toefl ? 'uploaded' : '' }}">
                    <div class="d-flex align-items-start">
                        <span class="document-number">10</span>
                        <div class="flex-grow-1">
                            <div class="document-label">Sertifikat TOEFL</div>
                            <div class="document-note">
                                <i class="fas fa-exclamation-triangle"></i> Min. 450 (maksimal 2 tahun terakhir)
                            </div>
                            @if($dokumen->sertifikat_toefl)
                                <span class="status-badge status-uploaded">
                                    <i class="fas fa-check-circle"></i> Sudah Diupload
                                </span>
                                <a href="{{ asset('storage/' . $dokumen->sertifikat_toefl) }}" target="_blank" class="view-button ms-2">
                                    <i class="fas fa-eye"></i> Lihat File
                                </a>
                            @else
                                <div class="file-input-custom">
                                    <input type="file" name="sertifikat_toefl" class="form-control" accept=".pdf">
                                    <div class="file-info">
                                        <i class="fas fa-info-circle"></i> Format: PDF | Max: 5MB
                                    </div>
                                </div>
                                <span class="status-badge status-missing">
                                    <i class="fas fa-exclamation-circle"></i> Belum Diupload
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 11. Rancangan Penelitian Singkat -->
                <div class="document-item {{ $dokumen->rancangan_penelitian ? 'uploaded' : '' }}">
                    <div class="d-flex align-items-start">
                        <span class="document-number">11</span>
                        <div class="flex-grow-1">
                            <div class="document-label">Rancangan Penelitian Singkat</div>
                            @if($dokumen->rancangan_penelitian)
                                <span class="status-badge status-uploaded">
                                    <i class="fas fa-check-circle"></i> Sudah Diupload
                                </span>
                                <a href="{{ asset('storage/' . $dokumen->rancangan_penelitian) }}" target="_blank" class="view-button ms-2">
                                    <i class="fas fa-eye"></i> Lihat File
                                </a>
                            @else
                                <div class="file-input-custom">
                                    <input type="file" name="rancangan_penelitian" class="form-control" accept=".pdf">
                                    <div class="file-info">
                                        <i class="fas fa-info-circle"></i> Format: PDF | Max: 5MB
                                    </div>
                                </div>
                                <span class="status-badge status-missing">
                                    <i class="fas fa-exclamation-circle"></i> Belum Diupload
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 12. SK Mampu Menggunakan Komputer -->
                <div class="document-item {{ $dokumen->sk_mampu_komputer ? 'uploaded' : '' }}">
                    <div class="d-flex align-items-start">
                        <span class="document-number">12</span>
                        <div class="flex-grow-1">
                            <div class="document-label">SK Mampu Menggunakan Komputer</div>
                            @if($dokumen->sk_mampu_komputer)
                                <span class="status-badge status-uploaded">
                                    <i class="fas fa-check-circle"></i> Sudah Diupload
                                </span>
                                <a href="{{ asset('storage/' . $dokumen->sk_mampu_komputer) }}" target="_blank" class="view-button ms-2">
                                    <i class="fas fa-eye"></i> Lihat File
                                </a>
                            @else
                                <div class="file-input-custom">
                                    <input type="file" name="sk_mampu_komputer" class="form-control" accept=".pdf">
                                    <div class="file-info">
                                        <i class="fas fa-info-circle"></i> Format: PDF | Max: 5MB
                                    </div>
                                </div>
                                <span class="status-badge status-missing">
                                    <i class="fas fa-exclamation-circle"></i> Belum Diupload
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 13. Bukti Tes Potensi Akademik (TPA) -->
                <div class="document-item {{ $dokumen->bukti_tes_tpa ? 'uploaded' : '' }}">
                    <div class="d-flex align-items-start">
                        <span class="document-number">13</span>
                        <div class="flex-grow-1">
                            <div class="document-label">Bukti telah mengikuti Tes Potensi Akademik (TPA)</div>
                            @if($dokumen->bukti_tes_tpa)
                                <span class="status-badge status-uploaded">
                                    <i class="fas fa-check-circle"></i> Sudah Diupload
                                </span>
                                <a href="{{ asset('storage/' . $dokumen->bukti_tes_tpa) }}" target="_blank" class="view-button ms-2">
                                    <i class="fas fa-eye"></i> Lihat File
                                </a>
                            @else
                                <div class="file-input-custom">
                                    <input type="file" name="bukti_tes_tpa" class="form-control" accept=".pdf">
                                    <div class="file-info">
                                        <i class="fas fa-info-circle"></i> Format: PDF | Max: 5MB
                                    </div>
                                </div>
                                <span class="status-badge status-missing">
                                    <i class="fas fa-exclamation-circle"></i> Belum Diupload
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 14. Mengikuti Seleksi Tes Substansi -->
                <div class="document-item {{ $dokumen->seleksi_tes_substansi ? 'uploaded' : '' }}">
                    <div class="d-flex align-items-start">
                        <span class="document-number">14</span>
                        <div class="flex-grow-1">
                            <div class="document-label">Mengikuti Seleksi Tes Substansi</div>
                            @if($dokumen->seleksi_tes_substansi)
                                <span class="status-badge status-uploaded">
                                    <i class="fas fa-check-circle"></i> Sudah Diupload
                                </span>
                                <a href="{{ asset('storage/' . $dokumen->seleksi_tes_substansi) }}" target="_blank" class="view-button ms-2">
                                    <i class="fas fa-eye"></i> Lihat File
                                </a>
                            @else
                                <div class="file-input-custom">
                                    <input type="file" name="seleksi_tes_substansi" class="form-control" accept=".pdf">
                                    <div class="file-info">
                                        <i class="fas fa-info-circle"></i> Format: PDF | Max: 5MB
                                    </div>
                                </div>
                                <span class="status-badge status-missing">
                                    <i class="fas fa-exclamation-circle"></i> Belum Diupload
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 15. Formulir Isian Foto -->
                <div class="document-item {{ $dokumen->formulir_isian_foto ? 'uploaded' : '' }}">
                    <div class="d-flex align-items-start">
                        <span class="document-number">15</span>
                        <div class="flex-grow-1">
                            <div class="document-label">Formulir Isian Foto</div>
                            @if($dokumen->formulir_isian_foto)
                                <span class="status-badge status-uploaded">
                                    <i class="fas fa-check-circle"></i> Sudah Diupload
                                </span>
                                <a href="{{ asset('storage/' . $dokumen->formulir_isian_foto) }}" target="_blank" class="view-button ms-2">
                                    <i class="fas fa-eye"></i> Lihat File
                                </a>
                            @else
                                <div class="file-input-custom">
                                    <input type="file" name="formulir_isian_foto" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                    <div class="file-info">
                                        <i class="fas fa-info-circle"></i> Format: JPG/PNG/PDF | Max: 2MB
                                    </div>
                                </div>
                                <span class="status-badge status-missing">
                                    <i class="fas fa-exclamation-circle"></i> Belum Diupload
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn-upload">
                        <i class="fas fa-cloud-upload-alt"></i> Upload Dokumen
                    </button>
                </div>
            </form>
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
                    const fileSize = file.size; // dalam bytes
                    const fileExtension = fileName.split('.').pop().toLowerCase();
                    
                    // Tentukan batas ukuran berdasarkan tipe file
                    let maxSize;
                    let maxSizeText;
                    
                    if (['jpg', 'jpeg', 'png'].includes(fileExtension)) {
                        maxSize = 2 * 1024 * 1024; // 2MB untuk gambar
                        maxSizeText = '2MB';
                    } else if (fileExtension === 'pdf') {
                        maxSize = 5 * 1024 * 1024; // 5MB untuk PDF
                        maxSizeText = '5MB';
                    } else {
                        alert('Format file tidak didukung! Harap upload file dengan format yang sesuai.');
                        e.target.value = '';
                        return;
                    }
                    
                    // Cek ukuran file
                    if (fileSize > maxSize) {
                        const fileSizeMB = (fileSize / (1024 * 1024)).toFixed(2);
                        alert(`Ukuran file terlalu besar (${fileSizeMB}MB)!\\nMaksimal ukuran file adalah ${maxSizeText}.\\nSilakan kompres atau pilih file lain.`);
                        e.target.value = ''; // Reset input
                        return;
                    }
                    
                    // Tampilkan info file yang dipilih
                    const fileSizeMB = (fileSize / (1024 * 1024)).toFixed(2);
                    const fileInfo = e.target.closest('.file-input-custom').querySelector('.file-info');
                    if (fileInfo) {
                        fileInfo.innerHTML = `<i class="fas fa-check-circle text-success"></i> File: ${fileName} (${fileSizeMB}MB)`;
                    }
                });
            });
            
            // Validasi sebelum submit form
            const uploadForm = document.querySelector('form');
            if (uploadForm) {
                uploadForm.addEventListener('submit', function(e) {
                    const fileInputs = this.querySelectorAll('input[type="file"]');
                    let hasFile = false;
                    
                    fileInputs.forEach(input => {
                        if (input.files.length > 0) {
                            hasFile = true;
                        }
                    });
                    
                    if (!hasFile) {
                        e.preventDefault();
                        alert('Harap pilih minimal satu file untuk diupload!');
                        return false;
                    }
                });
            }
        });
    </script>
</body>
</html>
