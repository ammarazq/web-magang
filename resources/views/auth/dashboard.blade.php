<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Pendaftaran</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 24px;
        }
        .navbar .btn-logout {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid white;
            padding: 8px 20px;
            border-radius: 25px;
            transition: all 0.3s;
        }
        .navbar .btn-logout:hover {
            background: white;
            color: #667eea;
        }
        .dashboard-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .card h3 {
            color: #333;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #666;
        }
        .info-value {
            color: #333;
        }
        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-warning {
            background: #ffc107;
            color: #000;
        }
        .badge-success {
            background: #28a745;
            color: white;
        }
        .badge-info {
            background: #17a2b8;
            color: white;
        }
        .badge-danger {
            background: #dc3545;
            color: white;
        }
        .progress {
            height: 25px;
            border-radius: 10px;
            background: #e9ecef;
        }
        .progress-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        .upload-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .upload-section h5 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .file-input-wrapper {
            margin-bottom: 15px;
        }
        .file-input-wrapper label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }
        .file-input-wrapper small {
            color: #666;
            font-size: 12px;
        }
        .file-input-wrapper input[type="file"] {
            border: 2px dashed #667eea;
            border-radius: 8px;
            padding: 10px;
            width: 100%;
        }
        .file-status {
            margin-top: 5px;
            font-size: 13px;
        }
        .file-uploaded {
            color: #28a745;
            font-weight: 600;
        }
        .file-not-uploaded {
            color: #dc3545;
        }
        .btn-upload {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container-fluid" style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
            <span class="navbar-brand">Dashboard Mahasiswa</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-logout">Logout</button>
            </form>
        </div>
    </nav>

    <div class="dashboard-container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(isset($mahasiswa))
            <div class="card">
                <h3>📋 Informasi Mahasiswa</h3>
                <div class="info-item">
                    <span class="info-label">No. Registrasi:</span>
                    <span class="info-value"><strong>{{ $mahasiswa->id }}</strong></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Nama Lengkap:</span>
                    <span class="info-value">{{ $mahasiswa->nama_lengkap }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $mahasiswa->email }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Program:</span>
                    <span class="info-value">{{ ucfirst($mahasiswa->jenis_pendaftaran) }}</span>
                </div>
                @if($mahasiswa->jalur_program)
                <div class="info-item">
                    <span class="info-label">Jalur Program:</span>
                    <span class="info-value">
                        <span class="badge badge-info">{{ $mahasiswa->jalur_program }}</span>
                    </span>
                </div>
                @endif
                @if($mahasiswa->program_studi)
                <div class="info-item">
                    <span class="info-label">Program Studi:</span>
                    <span class="info-value">{{ $mahasiswa->program_studi }}</span>
                </div>
                @endif
                <div class="info-item">
                    <span class="info-label">Status Verifikasi:</span>
                    <span class="info-value">
                        @if($mahasiswa->status_verifikasi == 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @elseif($mahasiswa->status_verifikasi == 'verified')
                            <span class="badge badge-success">Terverifikasi</span>
                        @else
                            <span class="badge badge-danger">Ditolak</span>
                        @endif
                    </span>
                </div>
            </div>

            @if(isset($dokumen))
            <div class="card">
                <h3>📁 Upload Dokumen</h3>
                
                <div style="margin-bottom: 25px;">
                    <p style="margin-bottom: 10px; color: #666; font-weight: 600;">
                        Kelengkapan Dokumen: {{ $dokumen->getPersentaseKelengkapan() }}%
                    </p>
                    <div class="progress">
                        <div class="progress-bar" style="width: {{ $dokumen->getPersentaseKelengkapan() }}%">
                            {{ $dokumen->getPersentaseKelengkapan() }}%
                        </div>
                    </div>
                </div>

                <form action="{{ route('upload.dokumen') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if($mahasiswa->jalur_program === 'Non RPL')
                        <div class="upload-section">
                            <h5>📄 Dokumen Wajib - Non RPL</h5>
                            
                            <div class="file-input-wrapper">
                                <label>1. Fotocopy Ijazah SLTA Legalisir</label>
                                <input type="file" name="ijazah_slta" accept=".pdf" class="form-control">
                                <small>Format: PDF | Max: 2MB</small>
                                @if($dokumen->ijazah_slta)
                                    <p class="file-status file-uploaded">✓ Sudah diupload</p>
                                @else
                                    <p class="file-status file-not-uploaded">✗ Belum diupload</p>
                                @endif
                            </div>

                            <div class="file-input-wrapper">
                                <label>2. Foto Formal</label>
                                <input type="file" name="foto_formal" accept=".jpg,.jpeg" class="form-control">
                                <small>Format: JPG/JPEG | Max: 2MB</small>
                                @if($dokumen->foto_formal)
                                    <p class="file-status file-uploaded">✓ Sudah diupload</p>
                                @else
                                    <p class="file-status file-not-uploaded">✗ Belum diupload</p>
                                @endif
                            </div>

                            <div class="file-input-wrapper">
                                <label>3. Scan KTP Asli</label>
                                <input type="file" name="ktp" accept=".jpg,.jpeg" class="form-control">
                                <small>Format: JPG/JPEG | Max: 2MB</small>
                                @if($dokumen->ktp)
                                    <p class="file-status file-uploaded">✓ Sudah diupload</p>
                                @else
                                    <p class="file-status file-not-uploaded">✗ Belum diupload</p>
                                @endif
                            </div>

                            <div class="file-input-wrapper">
                                <label>4. Formulir Keabsahan dan Kebenaran Dokumen</label>
                                <input type="file" name="formulir_keabsahan" accept=".jpg,.jpeg" class="form-control">
                                <small>Format: JPG/JPEG | Max: 2MB</small>
                                @if($dokumen->formulir_keabsahan)
                                    <p class="file-status file-uploaded">✓ Sudah diupload</p>
                                @else
                                    <p class="file-status file-not-uploaded">✗ Belum diupload</p>
                                @endif
                            </div>

                            <div class="file-input-wrapper">
                                <label>5. Formulir Pendaftaran</label>
                                <input type="file" name="formulir_pendaftaran" accept=".jpg,.jpeg" class="form-control">
                                <small>Format: JPG/JPEG | Max: 2MB</small>
                                @if($dokumen->formulir_pendaftaran)
                                    <p class="file-status file-uploaded">✓ Sudah diupload</p>
                                @else
                                    <p class="file-status file-not-uploaded">✗ Belum diupload</p>
                                @endif
                            </div>
                        </div>

                    @elseif($mahasiswa->jalur_program === 'RPL')
                        <div class="upload-section">
                            <h5>📄 Dokumen Wajib - RPL</h5>
                            
                            <div class="file-input-wrapper">
                                <label>1. Fotocopy Ijazah Pendidikan Terakhir Legalisir</label>
                                <input type="file" name="ijazah_pendidikan_terakhir" accept=".pdf" class="form-control">
                                <small>Format: PDF | Max: 2MB</small>
                                @if($dokumen->ijazah_pendidikan_terakhir)
                                    <p class="file-status file-uploaded">✓ Sudah diupload</p>
                                @else
                                    <p class="file-status file-not-uploaded">✗ Belum diupload</p>
                                @endif
                            </div>

                            <div class="file-input-wrapper">
                                <label>2. Fotocopy Transkrip Nilai Pendidikan Terakhir Legalisir</label>
                                <input type="file" name="transkrip_nilai" accept=".pdf" class="form-control">
                                <small>Format: PDF | Max: 2MB</small>
                                @if($dokumen->transkrip_nilai)
                                    <p class="file-status file-uploaded">✓ Sudah diupload</p>
                                @else
                                    <p class="file-status file-not-uploaded">✗ Belum diupload</p>
                                @endif
                            </div>

                            <div class="file-input-wrapper">
                                <label>3. Scan Ijazah SLTA Asli</label>
                                <input type="file" name="ijazah_slta_asli" accept=".pdf" class="form-control">
                                <small>Format: PDF | Max: 2MB</small>
                                @if($dokumen->ijazah_slta_asli)
                                    <p class="file-status file-uploaded">✓ Sudah diupload</p>
                                @else
                                    <p class="file-status file-not-uploaded">✗ Belum diupload</p>
                                @endif
                            </div>

                            <div class="file-input-wrapper">
                                <label>4. Foto Formal</label>
                                <input type="file" name="foto_formal" accept=".jpg,.jpeg" class="form-control">
                                <small>Format: JPG/JPEG | Max: 2MB</small>
                                @if($dokumen->foto_formal)
                                    <p class="file-status file-uploaded">✓ Sudah diupload</p>
                                @else
                                    <p class="file-status file-not-uploaded">✗ Belum diupload</p>
                                @endif
                            </div>

                            <div class="file-input-wrapper">
                                <label>5. Scan KTP Asli</label>
                                <input type="file" name="ktp" accept=".jpg,.jpeg" class="form-control">
                                <small>Format: JPG/JPEG | Max: 2MB</small>
                                @if($dokumen->ktp)
                                    <p class="file-status file-uploaded">✓ Sudah diupload</p>
                                @else
                                    <p class="file-status file-not-uploaded">✗ Belum diupload</p>
                                @endif
                            </div>

                            <div class="file-input-wrapper">
                                <label>6. Formulir Keabsahan dan Kebenaran Dokumen</label>
                                <input type="file" name="formulir_keabsahan" accept=".jpg,.jpeg" class="form-control">
                                <small>Format: JPG/JPEG | Max: 2MB</small>
                                @if($dokumen->formulir_keabsahan)
                                    <p class="file-status file-uploaded">✓ Sudah diupload</p>
                                @else
                                    <p class="file-status file-not-uploaded">✗ Belum diupload</p>
                                @endif
                            </div>

                            <div class="file-input-wrapper">
                                <label>7. Formulir Pendaftaran</label>
                                <input type="file" name="formulir_pendaftaran" accept=".jpg,.jpeg" class="form-control">
                                <small>Format: JPG/JPEG | Max: 2MB</small>
                                @if($dokumen->formulir_pendaftaran)
                                    <p class="file-status file-uploaded">✓ Sudah diupload</p>
                                @else
                                    <p class="file-status file-not-uploaded">✗ Belum diupload</p>
                                @endif
                            </div>

                            <div class="file-input-wrapper">
                                <label>8. Daftar Riwayat Hidup</label>
                                <input type="file" name="riwayat_hidup" accept=".pdf" class="form-control">
                                <small>Format: PDF | Max: 2MB</small>
                                @if($dokumen->riwayat_hidup)
                                    <p class="file-status file-uploaded">✓ Sudah diupload</p>
                                @else
                                    <p class="file-status file-not-uploaded">✗ Belum diupload</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div style="text-align: center; margin-top: 20px;">
                        <button type="submit" class="btn btn-upload">📤 Upload Dokumen</button>
                    </div>
                </form>

                <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
                    <h5 style="color: #667eea; font-weight: 600; margin-bottom: 10px;">Status Dokumen:</h5>
                    <p style="margin: 0;">
                        @if($dokumen->status_dokumen == 'belum_lengkap')
                            <span class="badge badge-warning">Belum Lengkap</span>
                            <small style="display: block; margin-top: 5px; color: #666;">
                                Silakan upload semua dokumen yang diperlukan
                            </small>
                        @elseif($dokumen->status_dokumen == 'lengkap')
                            <span class="badge badge-success">Lengkap - Menunggu Verifikasi</span>
                        @elseif($dokumen->status_dokumen == 'diverifikasi')
                            <span class="badge badge-success">Diverifikasi</span>
                        @else
                            <span class="badge badge-danger">Ditolak</span>
                        @endif
                    </p>
                </div>
            </div>
            @endif
        @else
            <div class="card">
                <h3>Selamat Datang, {{ $user->name }}!</h3>
            </div>
        @endif
    </div>
</body>
</html>
