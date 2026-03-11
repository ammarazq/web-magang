# Google Drive Integration - Quick Reference

## Fitur Hybrid Storage System

Portal Mahasiswa menggunakan **Hybrid Storage Architecture**:
- **Local Storage** (primary): Untuk akses cepat admin saat verifikasi
- **Google Drive** (backup): Untuk keamanan dan redundancy cloud

## Konfigurasi Cepat

### 1. Environment Variables (.env)

```env
GOOGLE_DRIVE_ENABLED=true
GOOGLE_DRIVE_CLIENT_ID=your-client-id.apps.googleusercontent.com
GOOGLE_DRIVE_CLIENT_SECRET=your-client-secret
GOOGLE_DRIVE_ROOT_FOLDER="Dokumen Mahasiswa Portal"
GOOGLE_DRIVE_AUTO_BACKUP=true
```

### 2. Google Cloud Console Setup

1. Create project di [Google Cloud Console](https://console.cloud.google.com/)
2. Enable **Google Drive API**
3. Create **OAuth 2.0 credentials**
4. Add redirect URI: `http://localhost:8000/admin/google-drive/callback`

**Dokumentasi lengkap:** [GOOGLE_DRIVE_SETUP.md](GOOGLE_DRIVE_SETUP.md)

## Routes

### Admin Routes

```php
// Configuration & Status
GET  /admin/google-drive/status          // Check config & auth status
GET  /admin/google-drive/statistics      // View backup statistics

// OAuth Flow
GET  /admin/google-drive/authorize       // Start Google OAuth
GET  /admin/google-drive/callback        // OAuth callback (automatic)

// Backup Operations
POST /admin/google-drive/backup/{id}     // Backup single document
POST /admin/google-drive/backup-all      // Backup all documents

// File Operations  
GET  /admin/google-drive/download/{id}/{field}  // Download from Drive
DELETE /admin/google-drive/backup/{id}          // Delete backup
```

## Features

### 1. Auto Backup on Upload
When `GOOGLE_DRIVE_AUTO_BACKUP=true`, setiap dokumen yang di-upload mahasiswa otomatis ter-backup ke Google Drive.

### 2. Manual Backup
Admin bisa trigger backup manual:
- Per dokumen mahasiswa (dari detail page)
- Semua dokumen sekaligus (dari statistics page)

### 3. Cloud Recovery
Jika file lokal hilang/corrupt, bisa download dari Google Drive sebagai backup.

### 4. Status Tracking
Database mencatat:
- `google_drive_folder_id` - ID folder mahasiswa di Drive
- `google_drive_files` - JSON mapping field → file ID
- `is_backed_up` - Status backup (boolean)
- `last_backup_at` - Waktu backup terakhir

## File Structure in Google Drive

```
Google Drive
└── Dokumen Mahasiswa Portal/
    ├── 202012345 - Ahmad Santoso/
    │   ├── formulir_pendaftaran.pdf
    │   ├── ktp.jpg
    │   ├── ijazah_slta.pdf
    │   └── ... (all documents)
    ├── 202012346 - Budi Setiawan/
    │   └── ...
    └── ...
```

## Quick Start

### 1. Install Dependencies
```bash
composer require google/apiclient
```

### 2. Run Migration
```bash
php artisan migrate
```

### 3. Configure .env
Add Google Drive credentials to `.env`

### 4. Clear Cache
```bash
php artisan config:clear
```

### 5. Authorize
1. Login sebagai Admin
2. Buka: `/admin/google-drive/status`
3. Click **"Authorize Google Drive"**
4. Login dengan akun Google
5. Allow permissions

### 6. Test Backup
Dari detail dokumen mahasiswa, click **"Backup to Google Drive"**

## Service Class

**Location:** `app/Services/GoogleDriveService.php`

### Key Methods

```php
// OAuth & Authentication
public function initializeClient(): Google_Client
public function getAuthUrl(): string
public function handleCallback(string $code): void

// Folder Management
public function getOrCreateRootFolder(): string
public function createMahasiswaFolder(Mahasiswa $mahasiswa, string $rootFolderId): string

// File Operations
public function uploadFile(string $filePath, string $fileName, string $folderId): array
public function updateFile(string $fileId, string $newFilePath): bool
public function downloadFile(string $fileId): string
public function deleteFile(string $fileId): bool

// Backup Automation
public function backupDokumenMahasiswa(DokumenMahasiswa $dokumen): array
```

## Usage Examples

### Backup Single Document (Controller)

```php
use App\Services\GoogleDriveService;

public function backup($id, GoogleDriveService $driveService)
{
    $dokumen = DokumenMahasiswa::with('mahasiswa')->findOrFail($id);
    
    $result = $driveService->backupDokumenMahasiswa($dokumen);
    
    if ($result['success']) {
        return back()->with('success', 'Backup berhasil!');
    }
    
    return back()->with('error', $result['error']);
}
```

### Auto Backup After Upload

```php
// In your upload controller
use App\Services\GoogleDriveService;

public function store(Request $request, GoogleDriveService $driveService)
{
    // ... save files locally ...
    
    $dokumen->save();
    
    // Auto backup if enabled
    if (config('google-drive.enabled') && config('google-drive.auto_backup')) {
        $driveService->backupDokumenMahasiswa($dokumen);
    }
    
    return redirect()->back()->with('success', 'Upload berhasil!');
}
```

### Check Backup Status (Blade)

```blade
@if($dokumen->is_backed_up)
    <span class="badge bg-success">
        <i class="fas fa-cloud-check"></i> Backed up
    </span>
    <small>{{ count($dokumen->google_drive_files ?? []) }} files</small>
@else
    <span class="badge bg-secondary">Not backed up</span>
@endif
```

## Database Schema

### dokumen_mahasiswa Table

```php
// New columns for Google Drive
$table->string('google_drive_folder_id')->nullable();
$table->json('google_drive_files')->nullable();
$table->boolean('is_backed_up')->default(false);
$table->timestamp('last_backup_at')->nullable();
```

**Migration:** `2026_03_10_060433_add_google_drive_columns_to_dokumen_mahasiswa_table.php`

## Configuration

**File:** `config/google-drive.php`

```php
return [
    'enabled' => env('GOOGLE_DRIVE_ENABLED', false),
    'client_id' => env('GOOGLE_DRIVE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
    'redirect_uri' => env('APP_URL') . '/admin/google-drive/callback',
    'scopes' => [
        Google_Service_Drive::DRIVE_FILE, // Access only app-created files
    ],
    'root_folder_name' => env('GOOGLE_DRIVE_ROOT_FOLDER', 'Dokumen Mahasiswa Portal'),
    'auto_backup' => env('GOOGLE_DRIVE_AUTO_BACKUP', true),
    // ... more options
];
```

## Security

### API Scope
Menggunakan scope minimal: `https://www.googleapis.com/auth/drive.file`
- ✅ Hanya akses file yang dibuat oleh app
- ❌ Tidak bisa akses file pribadi user lain

### Credentials Storage
```bash
storage/app/google-drive/
├── credentials.json  (optional - OAuth config)
└── token.json        (OAuth access token)
```

**Important:** Jangan commit ke git!

```gitignore
storage/app/google-drive/
.env
```

## Troubleshooting

### "Invalid credentials"
- Check `GOOGLE_DRIVE_CLIENT_ID` dan `CLIENT_SECRET` di `.env`
- Run: `php artisan config:clear`

### "Redirect URI mismatch"
- Pastikan URL di Google Cloud Console exact match
- Development: `http://localhost:8000/admin/google-drive/callback`
- Production: `https://yourdomain.com/admin/google-drive/callback`

### "Token expired"
- Delete `storage/app/google-drive/token.json`
- Re-authorize dari `/admin/google-drive/authorize`

### Files not uploading
- Check `GOOGLE_DRIVE_ENABLED=true`
- Check token exists: `storage/app/google-drive/token.json`
- Check logs: `storage/logs/laravel.log`

## API Limits

Google Drive API (Free Tier):
- **1 billion queries/day** - Very generous
- **1,000 queries/100s per user** - More than enough
- **Storage:** 15GB free (or unlimited with Workspace)

## Resources

- **Full Setup Guide:** [GOOGLE_DRIVE_SETUP.md](GOOGLE_DRIVE_SETUP.md)
- **Google Drive API Docs:** https://developers.google.com/drive/api/v3/about-sdk
- **PHP Client Library:** https://github.com/googleapis/google-api-php-client

## Support

Jika ada masalah:
1. Check [GOOGLE_DRIVE_SETUP.md](GOOGLE_DRIVE_SETUP.md) Troubleshooting section
2. Check Laravel logs: `storage/logs/laravel.log`
3. Verify Google Cloud Console settings
4. Test dengan manual backup dulu sebelum enable auto-backup

---

**Last Updated:** March 10, 2026
