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
        .top-bar {
            background: #0a1128;
            color: #fff;
            padding: 8px 0;
            font-size: 13px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .social-icons a {
            color: #fff;
            margin-right: 15px;
            font-size: 14px;
            transition: color 0.3s;
        }
        .social-icons a:hover {
            color: #4facfe;
        }
        .top-contact {
            color: #fff;
        }
        .top-contact i {
            color: #4facfe;
            margin-right: 5px;
        }
        .navbar-modern {
            background: #1a1d3f;
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            color: #fff !important;
            font-weight: bold;
            font-size: 24px;
        }
        .brand-icon {
            color: #4facfe;
            font-size: 28px;
            margin-right: 10px;
        }
        .brand-text {
            color: #fff;
        }
        .navbar-modern .navbar-nav .nav-link {
            color: #fff !important;
            font-weight: 500;
            padding: 8px 20px !important;
            margin: 0 5px;
            font-size: 13px;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            text-transform: uppercase;
        }
        .navbar-modern .navbar-nav .nav-link:hover {
            color: #4facfe !important;
        }
        .navbar-modern .navbar-nav .nav-link.active {
            color: #4facfe !important;
            position: relative;
        }
        .navbar-modern .navbar-nav .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 20px;
            right: 20px;
            height: 2px;
            background: #4facfe;
        }
        .navbar-modern .dropdown-menu {
            background: #1a1d3f;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .navbar-modern .dropdown-item {
            color: #fff;
            transition: all 0.3s;
        }
        .navbar-modern .dropdown-item:hover {
            background: rgba(79, 172, 254, 0.1);
            color: #4facfe;
        }
        .navbar-modern .dropdown-divider {
            border-color: rgba(255, 255, 255, 0.1);
        }
        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.3);
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <span class="top-contact">
                        <i class="fas fa-phone-alt"></i> +62 123 456 789
                        <span class="ms-3"><i class="fas fa-envelope"></i> mahasiswa@portal.ac.id</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="navbar navbar-expand-lg navbar-modern">
        <div class="container">
            <a class="navbar-brand" href="{{ route('mahasiswa.dashboard') }}">
                <i class="fas fa-fire brand-icon"></i>
                <span class="brand-text">Portal Mahasiswa</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('mahasiswa.dashboard') }}">
                            DASHBOARD
                        </a>
                    </li>
                    @if($mahasiswa->jenjang === 'S2')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('magister.upload') }}">
                                UPLOAD DOKUMEN S2
                            </a>
                        </li>
                    @elseif($mahasiswa->jenjang === 'S3')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('doktoral.upload') }}">
                                UPLOAD DOKUMEN S3
                            </a>
                        </li>
                    @elseif(in_array($mahasiswa->jenjang, ['D3', 'D4', 'S1']))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('sarjana.upload') }}">
                                UPLOAD DOKUMEN
                            </a>
                        </li>
                    @endif
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-id-card"></i> {{ $mahasiswa->jenjang }} - {{ $mahasiswa->nama_program_studi }}
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
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
                        <p class="text-muted mb-1">{{ $mahasiswa->jenjang }} - {{ $mahasiswa->nama_program_studi }}</p>
                        <p class="badge bg-info">{{ $mahasiswa->jalur_program }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Status Dokumen -->
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

                <!-- Konten Utama Upload Dokumen -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-upload"></i> Upload Dokumen Pendaftaran
                    </div>
                    <div class="card-body text-center py-5">
                        @if($mahasiswa->jenjang === 'S2')
                            <div class="mb-4">
                                <i class="fas fa-graduation-cap fa-4x text-primary mb-3"></i>
                                <h4>Program Magister (S2)</h4>
                                <p class="text-muted">Upload dokumen pendaftaran sesuai persyaratan</p>
                                <p class="mb-3"><strong>Jumlah Dokumen:</strong> 10 dokumen wajib</p>
                            </div>
                            <a href="{{ route('magister.upload') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-file-upload"></i> Upload Dokumen S2
                            </a>
                        @elseif($mahasiswa->jenjang === 'S3')
                            <div class="mb-4">
                                <i class="fas fa-user-graduate fa-4x text-primary mb-3"></i>
                                <h4>Program Doktoral (S3)</h4>
                                <p class="text-muted">Upload dokumen pendaftaran sesuai persyaratan</p>
                                <p class="mb-3"><strong>Jumlah Dokumen:</strong> 11 dokumen wajib</p>
                            </div>
                            <a href="{{ route('doktoral.upload') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-file-upload"></i> Upload Dokumen S3
                            </a>
                        @elseif(in_array($mahasiswa->jenjang, ['D3', 'D4', 'S1']))
                            <div class="mb-4">
                                <i class="fas fa-university fa-4x text-primary mb-3"></i>
                                <h4>Program {{ $mahasiswa->jenjang }} @if($mahasiswa->jalur_program === 'RPL')(RPL)@endif</h4>
                                <p class="text-muted">Upload dokumen pendaftaran sesuai persyaratan</p>
                                @if($mahasiswa->jalur_program === 'RPL')
                                    <p class="mb-3"><strong>Jumlah Dokumen:</strong> 6 dokumen wajib + 1 opsional</p>
                                @else
                                    <p class="mb-3"><strong>Jumlah Dokumen:</strong> 5 dokumen wajib</p>
                                @endif
                            </div>
                            <a href="{{ route('sarjana.upload') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-file-upload"></i> Upload Dokumen {{ $mahasiswa->jenjang }}
                            </a>
                        @endif

                        <div class="mt-4">
                            <div class="alert alert-info text-start">
                                <i class="fas fa-info-circle"></i> <strong>Informasi:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Pastikan semua dokumen dalam format yang benar (PDF/JPG)</li>
                                    <li>Ukuran maksimal file: 2MB per dokumen</li>
                                    <li>Upload dokumen dengan jelas dan terbaca</li>
                                    <li>Dokumen yang sudah diupload bisa diupdate/diganti</li>
                                </ul>
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
                                <a href="{{ asset('dokumen_mahasiswa/' . $dokumen->$field) }}" target="_blank" class="btn btn-sm btn-outline-primary float-end">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Pesan Jika Sudah Diverifikasi -->
                @if($dokumen->status_dokumen === 'diverifikasi')
                <div class="card">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5 class="text-success">Dokumen Sudah Diverifikasi</h5>
                        <p class="text-muted mb-0">Dokumen Anda sudah diverifikasi dan disetujui oleh admin.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
