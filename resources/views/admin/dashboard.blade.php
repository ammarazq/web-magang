<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            color: white;
            margin-bottom: 20px;
        }
        .stat-card i {
            font-size: 3rem;
            opacity: 0.7;
        }
        .stat-total { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-pending { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-verified { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .stat-rejected { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            cursor: pointer;
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
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-bolt"></i> Menu Cepat</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('admin.verifikasi.list') }}" class="text-decoration-none">
                                    <div class="card border-primary h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-clipboard-check fa-3x text-primary mb-3"></i>
                                            <h5>Verifikasi Dokumen</h5>
                                            <p class="text-muted">Lihat dan verifikasi dokumen mahasiswa</p>
                                            <span class="badge bg-warning">{{ $stats['menunggu_verifikasi'] }} Menunggu</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('admin.users') }}" class="text-decoration-none">
                                    <div class="card border-success h-100">
                                        <div class="card-body text-center">
                                            <i class="fas fa-users-cog fa-3x text-success mb-3"></i>
                                            <h5>User Management</h5>
                                            <p class="text-muted">Kelola user dan admin</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card border-info h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-chart-bar fa-3x text-info mb-3"></i>
                                        <h5>Laporan</h5>
                                        <p class="text-muted">Lihat laporan statistik</p>
                                        <span class="badge bg-secondary">Segera Hadir</span>
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
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-chart-line"></i> Progress Upload Dokumen Mahasiswa</h5>
                        <a href="{{ route('admin.verifikasi.list') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-list"></i> Lihat Semua
                        </a>
                    </div>
                    <div class="card-body">
                        @php
                            // Get all mahasiswa with their dokumen, ordered by latest update
                            $recentMahasiswa = \App\Models\Mahasiswa::with(['dokumen'])
                                ->orderBy('updated_at', 'desc')
                                ->limit(10)
                                ->get();
                        @endphp

                        @if($recentMahasiswa->count() > 0)
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
                                        @foreach($recentMahasiswa as $mhs)
                                            @php
                                                if ($mhs->dokumen) {
                                                    $persentase = $mhs->dokumen->getPersentaseKelengkapan();
                                                    $jumlahDokumen = $mhs->dokumen->getJumlahDokumen();
                                                } else {
                                                    $persentase = 0;
                                                    $jumlahDokumen = ['uploaded' => 0, 'total' => 0];
                                                }
                                                
                                                // Tentukan class warna berdasarkan persentase
                                                if ($persentase == 100) {
                                                    $colorClass = 'bg-success';
                                                    $textClass = 'text-success';
                                                } elseif ($persentase >= 70) {
                                                    $colorClass = 'bg-info';
                                                    $textClass = 'text-info';
                                                } elseif ($persentase >= 40) {
                                                    $colorClass = 'bg-warning';
                                                    $textClass = 'text-warning';
                                                } else {
                                                    $colorClass = 'bg-danger';
                                                    $textClass = 'text-danger';
                                                }
                                            @endphp
                                            <tr>
                                                <td>
                                                    <strong>{{ $mhs->nama_lengkap }}</strong><br>
                                                    <small class="text-muted">{{ $mhs->email }}</small>
                                                </td>
                                                <td><span class="badge bg-info">{{ $mhs->jenjang }}</span></td>
                                                <td>{{ $mhs->getNamaProgramStudi() }}</td>
                                                <td>
                                                    <div class="progress" style="height: 25px;">
                                                        <div class="progress-bar {{ $colorClass }}" 
                                                             role="progressbar" 
                                                             style="width: {{ $persentase }}%;" 
                                                             aria-valuenow="{{ $persentase }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                            <strong>{{ $persentase }}%</strong>
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="fas fa-file-alt"></i> 
                                                        {{ $jumlahDokumen['uploaded'] }}/{{ $jumlahDokumen['total'] }} dokumen
                                                    </small>
                                                </td>
                                                <td>
                                                    @if($mhs->dokumen)
                                                        @if($mhs->dokumen->status_dokumen === 'belum_lengkap')
                                                            <span class="badge bg-warning">Belum Lengkap</span>
                                                        @elseif($mhs->dokumen->status_dokumen === 'lengkap')
                                                            <span class="badge bg-info">Lengkap</span>
                                                        @elseif($mhs->dokumen->status_dokumen === 'diverifikasi')
                                                            <span class="badge bg-success">Diverifikasi</span>
                                                        @elseif($mhs->dokumen->status_dokumen === 'ditolak')
                                                            <span class="badge bg-danger">Ditolak</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">Belum Upload</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($mhs->dokumen)
                                                        <a href="{{ route('admin.detail', $mhs->id) }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-eye"></i> Detail
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center text-muted">Belum ada mahasiswa yang terdaftar</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
