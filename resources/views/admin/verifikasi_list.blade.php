<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Dokumen - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .stat-card {
            border-radius: 10px;
            padding: 15px;
            color: white;
            margin-bottom: 20px;
        }
        .stat-card i {
            font-size: 2rem;
            opacity: 0.7;
        }
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

        <h4 class="mb-4"><i class="fas fa-clipboard-check"></i> Verifikasi Dokumen Mahasiswa</h4>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-4">
                <div class="stat-card stat-pending">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Menunggu Verifikasi</h6>
                            <h3 class="mb-0">{{ $stats['menunggu_verifikasi'] }}</h3>
                        </div>
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card stat-verified">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Sudah Diverifikasi</h6>
                            <h3 class="mb-0">{{ $stats['sudah_diverifikasi'] }}</h3>
                        </div>
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card stat-rejected">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Ditolak</h6>
                            <h3 class="mb-0">{{ $stats['ditolak'] }}</h3>
                        </div>
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-table"></i> Tabel Verifikasi Dokumen Mahasiswa
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Mahasiswa</th>
                                <th>Email</th>
                                <th>Jenjang</th>
                                <th>Program Studi</th>
                                <th>Jalur</th>
                                <th>Status Dokumen</th>
                                <th>Kelengkapan</th>
                                <th>Diverifikasi Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mahasiswaList as $mhs)
                                <tr>
                                    <td>{{ $loop->iteration + ($mahasiswaList->currentPage() - 1) * $mahasiswaList->perPage() }}</td>
                                    <td>
                                        <strong>{{ $mhs->nama_lengkap }}</strong><br>
                                        <small class="text-muted">ID: {{ $mhs->id }}</small>
                                    </td>
                                    <td>{{ $mhs->email }}</td>
                                    <td><span class="badge bg-info">{{ $mhs->jenjang }}</span></td>
                                    <td>{{ $mhs->program_studi }}</td>
                                    <td><span class="badge bg-secondary">{{ $mhs->jalur_program }}</span></td>
                                    <td>
                                        @if($mhs->dokumen)
                                            @if($mhs->dokumen->status_dokumen === 'belum_lengkap')
                                                <span class="badge bg-warning">Belum Lengkap</span>
                                            @elseif($mhs->dokumen->status_dokumen === 'lengkap')
                                                <span class="badge bg-info">Lengkap</span>
                                            @elseif($mhs->dokumen->status_dokumen === 'diverifikasi')
                                                <span class="badge bg-success">Diverifikasi</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Belum Upload</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($mhs->dokumen)
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $mhs->dokumen->getPersentaseKelengkapan() }}%">
                                                    {{ $mhs->dokumen->getPersentaseKelengkapan() }}%
                                                </div>
                                            </div>
                                        @else
                                            0%
                                        @endif
                                    </td>
                                    <td>
                                        @if($mhs->dokumen && $mhs->dokumen->verified_by)
                                            <small>
                                                {{ $mhs->dokumen->verifiedBy->name }}<br>
                                                <span class="text-muted">{{ $mhs->dokumen->verified_at->format('d M Y') }}</span>
                                            </small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($mhs->dokumen)
                                            <a href="{{ route('admin.detail', $mhs->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak ada dokumen</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">Tidak ada data mahasiswa</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $mahasiswaList->links() }}
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
