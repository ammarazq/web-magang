<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Drive Statistics - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: #f5f7fa;
        }
    </style>
</head>
<body>
    @include('admin.navbar')

    <div class="container-fluid my-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-bar-chart"></i> Google Drive Statistics</h2>
                <div>
                    <a href="{{ route('admin.google-drive.status') }}" class="btn btn-secondary">
                        <i class="bi bi-gear"></i> Configuration
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Dashboard
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">Total Documents</h6>
                                    <h2 class="mb-0">{{ $stats['total_documents'] }}</h2>
                                </div>
                                <div>
                                    <i class="bi bi-file-earmark-text fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">Backed Up</h6>
                                    <h2 class="mb-0">{{ $stats['backed_up'] }}</h2>
                                    <small>
                                        @if($stats['total_documents'] > 0)
                                            {{ number_format(($stats['backed_up'] / $stats['total_documents']) * 100, 1) }}%
                                        @else
                                            0%
                                        @endif
                                    </small>
                                </div>
                                <div>
                                    <i class="bi bi-cloud-check fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">Not Backed Up</h6>
                                    <h2 class="mb-0">{{ $stats['not_backed_up'] }}</h2>
                                    <small>
                                        @if($stats['total_documents'] > 0)
                                            {{ number_format(($stats['not_backed_up'] / $stats['total_documents']) * 100, 1) }}%
                                        @else
                                            0%
                                        @endif
                                    </small>
                                </div>
                                <div>
                                    <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">Total Files</h6>
                                    <h2 class="mb-0">{{ $stats['total_files'] }}</h2>
                                    <small>in Google Drive</small>
                                </div>
                                <div>
                                    <i class="bi bi-files fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last Backup Info -->
            @if($stats['last_backup'])
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-clock-history"></i> Last Backup</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Mahasiswa:</strong> 
                                    {{ $stats['last_backup']->mahasiswa->nama ?? 'N/A' }}
                                </p>
                                <p class="mb-1"><strong>NIM:</strong> 
                                    {{ $stats['last_backup']->mahasiswa->nim ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Time:</strong> 
                                    {{ $stats['last_backup']->last_backup_at->format('d M Y H:i:s') }}
                                </p>
                                <p class="mb-0"><strong>Files:</strong> 
                                    {{ is_array($stats['last_backup']->google_drive_files) ? count($stats['last_backup']->google_drive_files) : 0 }} 
                                    files
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Progress Bar -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-graph-up"></i> Backup Progress</h5>
                    @php
                        $percentage = $stats['total_documents'] > 0 
                            ? ($stats['backed_up'] / $stats['total_documents']) * 100 
                            : 0;
                    @endphp
                    <div class="progress" style="height: 30px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $percentage }}%" 
                             aria-valuenow="{{ $percentage }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ number_format($percentage, 1) }}% Complete
                        </div>
                    </div>
                    <p class="text-muted mt-2 mb-0">
                        <small>{{ $stats['backed_up'] }} out of {{ $stats['total_documents'] }} documents backed up</small>
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-tools"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <form action="{{ route('admin.google-drive.backup-all') }}" method="POST"
                                      onsubmit="return confirm('Backup semua dokumen yang belum di-backup? Ini mungkin memakan waktu lama.')">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="bi bi-cloud-upload"></i> Backup All Unbacked Documents
                                    </button>
                                </form>
                                <small class="text-muted mt-1">
                                    Will backup {{ $stats['not_backed_up'] }} documents
                                </small>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <a href="{{ route('admin.verifikasi.list') }}" class="btn btn-primary btn-lg">
                                    <i class="bi bi-list-check"></i> Go to Document Verification
                                </a>
                                <small class="text-muted mt-1">
                                    Manual backup available per document
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="card mt-4 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-lightbulb"></i> Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li><strong>Auto Backup:</strong> Enable <code>GOOGLE_DRIVE_AUTO_BACKUP=true</code> in .env to automatically backup new uploads</li>
                        <li><strong>Manual Backup:</strong> You can backup individual documents from the verification page</li>
                        <li><strong>Storage:</strong> Files are stored in your Google Drive under "{{ config('google-drive.root_folder_name') }}"</li>
                        <li><strong>Redundancy:</strong> Local files are kept for fast access, Drive backup provides safety</li>
                        <li><strong>Recovery:</strong> You can download files from Google Drive if local copies are lost</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
