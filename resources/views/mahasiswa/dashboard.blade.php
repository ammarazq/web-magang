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
