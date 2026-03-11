<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f5f7fa;
        }
        .stat-card {
            border-radius: 15px;
            padding: 25px;
            color: white;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .stat-card i {
            font-size: 3rem;
            opacity: 0.8;
        }
        .stat-total { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-pending { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-verified { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .stat-rejected { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }
        .card-header {
            border-radius: 15px 15px 0 0 !important;
            border: none;
        }
        .pagination .page-link {
            border-radius: 8px;
            padding: 8px 14px;
            margin: 0 4px;
            border: 1px solid #dee2e6;
            color: #495057;
            transition: all 0.3s;
        }
        .pagination .page-link:hover {
            background-color: #ff6b35;
            color: white;
            border-color: #ff6b35;
        }
        .pagination .page-item.active .page-link {
            background-color: #ff6b35;
            border-color: #ff6b35;
            color: white;
        }
        .pagination .page-item.disabled .page-link {
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
        .nav-pills .nav-link {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .nav-pills .nav-link:hover {
            background-color: rgba(255, 107, 53, 0.1);
        }
        .nav-pills .nav-link.active {
            background-color: #ff6b35;
        }
        .btn-primary {
            background-color: #ff6b35;
            border-color: #ff6b35;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: #e55a2a;
            border-color: #e55a2a;
        }
        .quick-action-card {
            transition: all 0.3s;
        }
        .quick-action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    @include('admin.navbar')

    <div class="container-fluid my-4">
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

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card stat-total">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total Mahasiswa</h6>
                            <h2 class="mb-0">{{ $stats['total_mahasiswa'] }}</h2>
                        </div>
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card stat-pending">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Menunggu Verifikasi</h6>
                            <h2 class="mb-0">{{ $stats['menunggu_verifikasi'] }}</h2>
                        </div>
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card stat-verified">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Sudah Diverifikasi</h6>
                            <h2 class="mb-0">{{ $stats['sudah_diverifikasi'] }}</h2>
                        </div>
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card stat-rejected">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Ditolak</h6>
                            <h2 class="mb-0">{{ $stats['ditolak'] }}</h2>
                        </div>
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <h5 class="mb-0"><i class="fas fa-bolt"></i> Menu Cepat</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.verifikasi.list') }}" class="text-decoration-none">
                                    <div class="card border-0 h-100 quick-action-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                                        <div class="card-body text-center p-4">
                                            <i class="fas fa-clipboard-check fa-3x mb-3" style="opacity: 0.9;"></i>
                                            <h5 class="fw-bold">Verifikasi Dokumen</h5>
                                            <p class="mb-3" style="opacity: 0.9;">Lihat dan verifikasi dokumen mahasiswa</p>
                                            <span class="badge bg-white text-dark">{{ $stats['menunggu_verifikasi'] }} Menunggu</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.users') }}" class="text-decoration-none">
                                    <div class="card border-0 h-100 quick-action-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                                        <div class="card-body text-center p-4">
                                            <i class="fas fa-users-cog fa-3x mb-3" style="opacity: 0.9;"></i>
                                            <h5 class="fw-bold">User Management</h5>
                                            <p class="mb-3" style="opacity: 0.9;">Kelola user dan admin</p>
                                            <span class="badge bg-white text-dark">Kelola Pengguna</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('admin.google-drive.status') }}" class="text-decoration-none">
                                    <div class="card border-0 h-100 quick-action-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                        <div class="card-body text-center p-4">
                                            <i class="fas fa-cloud fa-3x mb-3" style="opacity: 0.9;"></i>
                                            <h5 class="fw-bold">Google Drive</h5>
                                            <p class="mb-3" style="opacity: 0.9;">Backup & cloud storage</p>
                                            <span class="badge bg-white text-dark">Cloud Backup</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card border-0 h-100 quick-action-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
                                    <div class="card-body text-center p-4">
                                        <i class="fas fa-chart-bar fa-3x mb-3" style="opacity: 0.9;"></i>
                                        <h5 class="fw-bold">Laporan</h5>
                                        <p class="mb-3" style="opacity: 0.9;">Lihat laporan statistik</p>
                                        <span class="badge bg-white text-dark">Segera Hadir</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Tracking -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <h5 class="mb-0"><i class="fas fa-chart-line"></i> Progress Upload Dokumen Mahasiswa</h5>
                        <a href="{{ route('admin.verifikasi.list') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-list"></i> Lihat Semua
                        </a>
                    </div>
                    <div class="card-body p-4">
                        <!-- Filter Tabs -->
                        <ul class="nav nav-pills mb-4" id="statusFilter" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button" role="tab">
                                    <i class="fas fa-users"></i> Semua <span class="badge bg-white text-dark ms-1">{{ $stats['total_mahasiswa'] }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="belum-lengkap-tab" data-bs-toggle="pill" data-bs-target="#belum-lengkap" type="button" role="tab">
                                    <i class="fas fa-exclamation-triangle"></i> Belum Lengkap <span class="badge bg-warning ms-1">{{ $stats['belum_lengkap'] }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="siap-verifikasi-tab" data-bs-toggle="pill" data-bs-target="#siap-verifikasi" type="button" role="tab">
                                    <i class="fas fa-hourglass-half"></i> Siap Diverifikasi <span class="badge bg-info ms-1">{{ $stats['menunggu_verifikasi'] }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="diverifikasi-tab" data-bs-toggle="pill" data-bs-target="#diverifikasi" type="button" role="tab">
                                    <i class="fas fa-check-circle"></i> Diverifikasi <span class="badge bg-success ms-1">{{ $stats['sudah_diverifikasi'] }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="ditolak-tab" data-bs-toggle="pill" data-bs-target="#ditolak" type="button" role="tab">
                                    <i class="fas fa-times-circle"></i> Ditolak <span class="badge bg-danger ms-1">{{ $stats['ditolak'] }}</span>
                                </button>
                            </li>
                        </ul>

                        @php
                            // Filter by status from paginated data
                            $belumLengkap = $allMahasiswa->filter(function($mhs) {
                                return $mhs->dokumen && $mhs->dokumen->status_dokumen === 'belum_lengkap';
                            });

                            $siapVerifikasi = $allMahasiswa->filter(function($mhs) {
                                return $mhs->dokumen && $mhs->dokumen->status_dokumen === 'lengkap';
                            });

                            $diverifikasi = $allMahasiswa->filter(function($mhs) {
                                return $mhs->dokumen && $mhs->dokumen->status_dokumen === 'diverifikasi';
                            });

                            $ditolak = $allMahasiswa->filter(function($mhs) {
                                return $mhs->dokumen && $mhs->dokumen->status_dokumen === 'ditolak';
                            });
                        @endphp

                        <!-- Tab Content -->
                        <div class="tab-content" id="statusFilterContent">
                            <!-- All Tab -->
                            <div class="tab-pane fade show active" id="all" role="tabpanel">
                                @if($allMahasiswa->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Jenjang</th>
                                                    <th>Program Studi</th>
                                                    <th>Progress Upload</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($allMahasiswa as $mhs)
                                                    @include('admin.partials.mahasiswa-row', ['mhs' => $mhs])
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-center text-muted">Belum ada mahasiswa yang terdaftar</p>
                                @endif
                            </div>

                            <!-- Belum Lengkap Tab -->
                            <div class="tab-pane fade" id="belum-lengkap" role="tabpanel">
                                @if($belumLengkap->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Jenjang</th>
                                                    <th>Program Studi</th>
                                                    <th>Progress Upload</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($belumLengkap as $mhs)
                                                    @include('admin.partials.mahasiswa-row', ['mhs' => $mhs])
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-center text-muted">Tidak ada mahasiswa dengan dokumen belum lengkap</p>
                                @endif
                            </div>

                            <!-- Siap Verifikasi Tab -->
                            <div class="tab-pane fade" id="siap-verifikasi" role="tabpanel">
                                @if($siapVerifikasi->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Jenjang</th>
                                                    <th>Program Studi</th>
                                                    <th>Progress Upload</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($siapVerifikasi as $mhs)
                                                    @include('admin.partials.mahasiswa-row', ['mhs' => $mhs])
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-center text-muted">Tidak ada dokumen yang siap diverifikasi</p>
                                @endif
                            </div>

                            <!-- Diverifikasi Tab -->
                            <div class="tab-pane fade" id="diverifikasi" role="tabpanel">
                                @if($diverifikasi->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Jenjang</th>
                                                    <th>Program Studi</th>
                                                    <th>Progress Upload</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($diverifikasi as $mhs)
                                                    @include('admin.partials.mahasiswa-row', ['mhs' => $mhs])
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-center text-muted">Belum ada dokumen yang diverifikasi</p>
                                @endif
                            </div>

                            <!-- Ditolak Tab -->
                            <div class="tab-pane fade" id="ditolak" role="tabpanel">
                                @if($ditolak->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Jenjang</th>
                                                    <th>Program Studi</th>
                                                    <th>Progress Upload</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($ditolak as $mhs)
                                                    @include('admin.partials.mahasiswa-row', ['mhs' => $mhs])
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-center text-muted">Tidak ada dokumen yang ditolak</p>
                                @endif
                            </div>
                        </div>

                        <!-- Pagination -->
                        @if($allMahasiswa->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top gap-3">
                                <div class="text-muted flex-shrink-0">
                                    Menampilkan {{ $allMahasiswa->firstItem() }} - {{ $allMahasiswa->lastItem() }} dari {{ $allMahasiswa->total() }} mahasiswa
                                </div>
                                <div class="d-flex justify-content-end">
                                    {{ $allMahasiswa->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
