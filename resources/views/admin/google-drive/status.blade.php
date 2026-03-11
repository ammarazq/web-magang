<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Drive Configuration - Admin</title>
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
                <h2><i class="bi bi-cloud-upload"></i> Google Drive Configuration</h2>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
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

            <!-- Status Overview -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="bi bi-toggle-{{ $config['enabled'] ? 'on text-success' : 'off text-secondary' }} fs-1"></i>
                            <h5 class="mt-2">Feature Status</h5>
                            <p class="mb-0">
                                @if($config['enabled'])
                                    <span class="badge bg-success">Enabled</span>
                                @else
                                    <span class="badge bg-secondary">Disabled</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="bi bi-file-earmark-code{{ $config['configured'] ? '-fill text-success' : ' text-warning' }} fs-1"></i>
                            <h5 class="mt-2">Credentials</h5>
                            <p class="mb-0">
                                @if($config['configured'])
                                    <span class="badge bg-success">Configured</span>
                                @else
                                    <span class="badge bg-warning">Not Found</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="bi bi-shield-{{ $config['authenticated'] ? 'check text-success' : 'exclamation text-danger' }} fs-1"></i>
                            <h5 class="mt-2">Authentication</h5>
                            <p class="mb-0">
                                @if($config['authenticated'])
                                    <span class="badge bg-success">Authenticated</span>
                                @else
                                    <span class="badge bg-danger">Not Authorized</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="bi bi-robot{{ $config['auto_backup'] ? ' text-primary' : ' text-secondary' }} fs-1"></i>
                            <h5 class="mt-2">Auto Backup</h5>
                            <p class="mb-0">
                                @if($config['auto_backup'])
                                    <span class="badge bg-primary">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuration Details -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-gear"></i> Configuration Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="250"><strong>Feature Enabled:</strong></td>
                            <td>
                                @if($config['enabled'])
                                    <span class="text-success"><i class="bi bi-check-circle-fill"></i> Yes</span>
                                @else
                                    <span class="text-danger"><i class="bi bi-x-circle-fill"></i> No</span>
                                    <div class="alert alert-warning mt-2 mb-0">
                                        <small>Set <code>GOOGLE_DRIVE_ENABLED=true</code> in .env file</small>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Client ID:</strong></td>
                            <td>
                                @if(config('google-drive.client_id'))
                                    <code>{{ Str::limit(config('google-drive.client_id'), 40) }}</code>
                                @else
                                    <span class="text-danger">Not configured</span>
                                    <div class="alert alert-warning mt-2 mb-0">
                                        <small>Add <code>GOOGLE_DRIVE_CLIENT_ID</code> to .env</small>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Client Secret:</strong></td>
                            <td>
                                @if(config('google-drive.client_secret'))
                                    <code>{{ Str::limit(config('google-drive.client_secret'), 20, '***') }}</code>
                                @else
                                    <span class="text-danger">Not configured</span>
                                    <div class="alert alert-warning mt-2 mb-0">
                                        <small>Add <code>GOOGLE_DRIVE_CLIENT_SECRET</code> to .env</small>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Root Folder:</strong></td>
                            <td><code>{{ config('google-drive.root_folder_name') }}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Auto Backup:</strong></td>
                            <td>
                                @if($config['auto_backup'])
                                    <span class="text-success"><i class="bi bi-check-circle-fill"></i> Enabled</span>
                                    <small class="text-muted d-block">Documents will auto-backup on upload</small>
                                @else
                                    <span class="text-secondary"><i class="bi bi-x-circle-fill"></i> Disabled</span>
                                    <small class="text-muted d-block">Manual backup required</small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Token File:</strong></td>
                            <td>
                                @if($config['authenticated'])
                                    <span class="text-success"><i class="bi bi-check-circle-fill"></i> Valid</span>
                                    <small class="text-muted d-block">{{ storage_path('app/google-drive/token.json') }}</small>
                                @else
                                    <span class="text-danger"><i class="bi bi-x-circle-fill"></i> Missing</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-play-circle"></i> Actions</h5>
                </div>
                <div class="card-body">
                    @if(!$config['enabled'])
                        <div class="alert alert-info">
                            <h6><i class="bi bi-info-circle"></i> Google Drive is disabled</h6>
                            <p class="mb-0">Enable Google Drive in your .env file and refresh this page.</p>
                        </div>
                    @elseif(!$config['configured'])
                        <div class="alert alert-warning">
                            <h6><i class="bi bi-exclamation-triangle"></i> Missing Credentials</h6>
                            <p>Please complete the following steps:</p>
                            <ol class="mb-0">
                                <li>Create OAuth credentials in Google Cloud Console</li>
                                <li>Add <code>GOOGLE_DRIVE_CLIENT_ID</code> and <code>GOOGLE_DRIVE_CLIENT_SECRET</code> to .env</li>
                                <li>Run <code>php artisan config:clear</code></li>
                                <li>Refresh this page</li>
                            </ol>
                            <hr>
                            <p class="mb-0">
                                <a href="{{ asset('GOOGLE_DRIVE_SETUP.md') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-book"></i> Read Setup Guide
                                </a>
                            </p>
                        </div>
                    @elseif(!$config['authenticated'])
                        <div class="alert alert-warning">
                            <h6><i class="bi bi-shield-exclamation"></i> Authorization Required</h6>
                            <p class="mb-2">Click the button below to authorize this application to access Google Drive.</p>
                            <a href="{{ route('admin.google-drive.authorize') }}" class="btn btn-primary">
                                <i class="bi bi-google"></i> Authorize Google Drive
                            </a>
                        </div>
                    @else
                        <div class="alert alert-success">
                            <h6><i class="bi bi-check-circle"></i> Google Drive is ready!</h6>
                            <p class="mb-0">You can now backup documents to Google Drive.</p>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6><i class="bi bi-bar-chart"></i> View Statistics</h6>
                                        <p class="small text-muted">Check backup status and storage usage</p>
                                        <a href="{{ route('admin.google-drive.statistics') }}" class="btn btn-sm btn-outline-primary">
                                            View Stats
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6><i class="bi bi-cloud-upload"></i> Backup All Documents</h6>
                                        <p class="small text-muted">Backup all student documents at once</p>
                                        <form action="{{ route('admin.google-drive.backup-all') }}" method="POST" 
                                              onsubmit="return confirm('Backup semua dokumen? Proses ini mungkin memakan waktu lama.')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-cloud-arrow-up"></i> Backup All
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6><i class="bi bi-arrow-clockwise"></i> Re-authorize</h6>
                                        <p class="small text-muted">Refresh Google Drive authorization</p>
                                        <a href="{{ route('admin.google-drive.authorize') }}" class="btn btn-sm btn-outline-secondary">
                                            Re-authorize
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Help Section -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-question-circle"></i> Need Help?</h5>
                </div>
                <div class="card-body">
                    <p><strong>Setup Instructions:</strong></p>
                    <ol>
                        <li>Read the complete setup guide: <a href="{{ url('GOOGLE_DRIVE_SETUP.md') }}" target="_blank">GOOGLE_DRIVE_SETUP.md</a></li>
                        <li>Create Google Cloud Project and enable Drive API</li>
                        <li>Configure OAuth consent screen</li>
                        <li>Create OAuth credentials</li>
                        <li>Add credentials to .env file</li>
                        <li>Authorize the application</li>
                    </ol>

                    <p class="mb-0"><strong>Common Issues:</strong></p>
                    <ul class="mb-0">
                        <li><strong>Redirect URI mismatch:</strong> Add exact callback URL in Google Cloud Console</li>
                        <li><strong>Invalid credentials:</strong> Double-check Client ID and Secret in .env</li>
                        <li><strong>Access blocked:</strong> Add test users in OAuth consent screen</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
