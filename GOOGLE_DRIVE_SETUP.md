# Google Drive Integration Setup Guide

Panduan lengkap untuk mengaktifkan fitur backup dokumen mahasiswa ke Google Drive.

## Table of Contents
1. [Prerequisites](#prerequisites)
2. [Google Cloud Console Setup](#google-cloud-console-setup)
3. [Laravel Configuration](#laravel-configuration)
4. [OAuth Authorization](#oauth-authorization)
5. [Testing](#testing)
6. [Troubleshooting](#troubleshooting)

---

## Prerequisites

- Akun Google (Gmail)
- Akses ke [Google Cloud Console](https://console.cloud.google.com/)
- PHP Google API Client sudah terinstall (via composer)
- Laravel aplikasi sudah running

---

## Google Cloud Console Setup

### Step 1: Create New Project

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Click dropdown project di bagian atas → **New Project**
3. Masukkan nama project: `Portal Mahasiswa Document Storage` (atau nama lain)
4. Click **Create**
5. Tunggu beberapa detik sampai project selesai dibuat

### Step 2: Enable Google Drive API

1. Dari dashboard project, buka menu **APIs & Services** → **Library**
2. Cari "Google Drive API"
3. Click pada **Google Drive API**
4. Click tombol **Enable**
5. Tunggu sampai API aktif (biasanya beberapa detik)

### Step 3: Configure OAuth Consent Screen

1. Buka **APIs & Services** → **OAuth consent screen**
2. Pilih **External** (untuk testing) atau **Internal** (jika punya Google Workspace)
3. Click **Create**

4. **App Information:**
   - App name: `Portal Mahasiswa`
   - User support email: [email admin Anda]
   - Developer contact: [email admin Anda]

5. **Scopes:**
   - Click **Add or Remove Scopes**
   - Filter cari: `drive.file`
   - Centang: `https://www.googleapis.com/auth/drive.file`
   - Click **Update**
   - Click **Save and Continue**

6. **Test Users** (untuk External app):
   - Click **Add Users**
   - Masukkan email yang akan digunakan untuk testing (email admin)
   - Click **Save and Continue**

7. **Summary:**
   - Review informasi
   - Click **Back to Dashboard**

### Step 4: Create OAuth Credentials

1. Buka **APIs & Services** → **Credentials**
2. Click **+ Create Credentials** → **OAuth client ID**

3. **Application type:** Web application

4. **Name:** `Portal Mahasiswa Web Client`

5. **Authorized redirect URIs:**
   - Click **+ Add URI**
   - Masukkan: `http://localhost:8000/admin/google-drive/callback`
   - Jika production, tambahkan juga: `https://yourdomain.com/admin/google-drive/callback`

6. Click **Create**

7. **Important:** Copy kedua nilai ini:
   - **Client ID**: Akan seperti `123456789-abcdefgh.apps.googleusercontent.com`
   - **Client Secret**: Akan seperti `GOCSPX-abc123def456`

### Step 5: Download Credentials JSON (Optional Alternative)

Cara alternatif menggunakan file JSON:

1. Dari halaman **Credentials**, click ikon **Download JSON** pada OAuth client yang baru dibuat
2. Rename file menjadi `credentials.json`
3. Copy file ke folder: `storage/app/google-drive/credentials.json`

---

## Laravel Configuration

### Step 1: Add Environment Variables

Edit file `.env` di root project:

```env
# Google Drive Configuration
GOOGLE_DRIVE_ENABLED=true
GOOGLE_DRIVE_CLIENT_ID=123456789-abcdefgh.apps.googleusercontent.com
GOOGLE_DRIVE_CLIENT_SECRET=GOCSPX-abc123def456
GOOGLE_DRIVE_ROOT_FOLDER="Dokumen Mahasiswa Portal"
GOOGLE_DRIVE_AUTO_BACKUP=true
```

**Penjelasan:**
- `GOOGLE_DRIVE_ENABLED`: Set `true` untuk mengaktifkan fitur
- `GOOGLE_DRIVE_CLIENT_ID`: Paste Client ID dari Step 4.7
- `GOOGLE_DRIVE_CLIENT_SECRET`: Paste Client Secret dari Step 4.7
- `GOOGLE_DRIVE_ROOT_FOLDER`: Nama folder utama di Google Drive (akan dibuat otomatis)
- `GOOGLE_DRIVE_AUTO_BACKUP`: Set `true` untuk auto-backup saat upload dokumen

### Step 2: Create Required Directories

```bash
# Buat folder untuk menyimpan token dan credentials
mkdir -p storage/app/google-drive
chmod -R 775 storage/app/google-drive
```

**Untuk Windows PowerShell:**
```powershell
New-Item -ItemType Directory -Force -Path storage\app\google-drive
```

### Step 3: Run Migration

Jika belum menjalankan migration untuk kolom Google Drive:

```bash
php artisan migrate
```

Ini akan menambahkan kolom:
- `google_drive_folder_id`
- `google_drive_files` (JSON)
- `is_backed_up`
- `last_backup_at`

### Step 4: Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
```

---

## OAuth Authorization

### Step 1: Access Authorization Page

1. Login sebagai **Admin** ke portal
2. Buka URL: `http://localhost:8000/admin/google-drive/status`
3. Anda akan melihat status konfigurasi Google Drive

### Step 2: Start Authorization Flow

1. Click tombol **"Authorize Google Drive"**
2. Anda akan diarahkan ke halaman Google
3. **Pilih akun Google** yang ingin digunakan

### Step 3: Grant Permissions

Google akan menampilkan permission request:

- "Portal Mahasiswa wants to access your Google Account"
- Scope: "See, edit, create, and delete only the specific Google Drive files you use with this app"

**Click "Allow"**

### Step 4: Verify Success

1. Anda akan di-redirect kembali ke portal
2. Muncul pesan: "Otorisasi Google Drive berhasil!"
3. Status page sekarang menampilkan: **Authenticated ✓**
4. File `token.json` otomatis dibuat di `storage/app/google-drive/`

---

## Testing

### Test 1: Manual Backup Single Document

1. Login sebagai **Admin**
2. Buka **Verifikasi Dokumen** → pilih mahasiswa
3. Click tombol **"Backup to Google Drive"**
4. Tunggu proses selesai
5. Pesan sukses muncul: "Backup berhasil! X dari Y dokumen ter-upload"

### Test 2: Verify in Google Drive

1. Buka [Google Drive](https://drive.google.com)
2. Cari folder: **"Dokumen Mahasiswa Portal"**
3. Di dalamnya ada sub-folder per mahasiswa: `{NIM} - {Nama}`
4. Buka folder mahasiswa, semua dokumen ada di sana

### Test 3: Auto Backup on Upload

1. Login sebagai **Mahasiswa**
2. Upload dokumen baru
3. **Jika `GOOGLE_DRIVE_AUTO_BACKUP=true`**, dokumen otomatis ter-backup
4. Check di Google Drive, file baru muncul

### Test 4: Download from Google Drive

1. Login sebagai **Admin**
2. Dari detail dokumen mahasiswa
3. Click link **"Download from Drive"** pada dokumen yang sudah di-backup
4. File akan didownload langsung dari Google Drive

### Test 5: Backup All Documents

1. Login sebagai **Admin**
2. Buka: `http://localhost:8000/admin/google-drive/statistics`
3. Click tombol **"Backup All Documents"**
4. Tunggu proses selesai (bisa lama jika banyak data)
5. Pesan menampilkan: berapa yang sukses, berapa yang gagal

---

## Features Overview

### Hybrid Storage Architecture

```
Upload Dokumen
     │
     ├─> Simpan ke Local Storage (public/dokumen_mahasiswa/)
     │   └─> Untuk akses cepat admin saat verifikasi
     │
     └─> Auto Backup ke Google Drive (opsional)
         └─> Untuk keamanan dan redundancy
```

### Available Admin Features

1. **Status Dashboard** (`/admin/google-drive/status`)
   - Check konfigurasi
   - Status autentikasi
   - Link untuk authorize

2. **Statistics** (`/admin/google-drive/statistics`)
   - Total dokumen
   - Berapa yang sudah di-backup
   - Berapa yang belum
   - Total file di cloud

3. **Manual Backup**
   - Backup per mahasiswa
   - Backup semua mahasiswa sekaligus

4. **Download from Drive**
   - Download file langsung dari Google Drive
   - Alternative jika local file hilang/corrupt

5. **Delete Backup**
   - Hapus backup dari Google Drive
   - Auto-update database status

---

## File Structure in Google Drive

```
Google Drive
└── Dokumen Mahasiswa Portal/              (Root folder)
    ├── 202012345 - Ahmad Santoso/         (Per mahasiswa)
    │   ├── formulir_pendaftaran.pdf
    │   ├── ktp.jpg
    │   ├── ijazah_slta.pdf
    │   └── ... (semua dokumen mahasiswa ini)
    │
    ├── 202012346 - Budi Setiawan/
    │   ├── formulir_pendaftaran.pdf
    │   └── ...
    │
    └── ... (folder mahasiswa lainnya)
```

---

## Troubleshooting

### Error: "Invalid credentials"

**Penyebab:** Client ID atau Secret salah

**Solusi:**
1. Double check nilai di `.env` dengan nilai di Google Cloud Console
2. Pastikan tidak ada spasi di awal/akhir
3. Jalankan: `php artisan config:clear`

---

### Error: "Redirect URI mismatch"

**Penyebab:** URL callback tidak sesuai dengan yang didaftarkan

**Solusi:**
1. Buka Google Cloud Console → Credentials
2. Edit OAuth client
3. Tambahkan exact URL: `http://localhost:8000/admin/google-drive/callback`
4. Untuk production: `https://yourdomain.com/admin/google-drive/callback`

---

### Error: "Access blocked: Portal Mahasiswa has not completed verification"

**Penyebab:** OAuth consent screen dalam status testing

**Solusi untuk Development:**
1. Tambahkan email Anda sebagai Test User
2. Atau publish app (baca dokumentasi Google)

**Solusi untuk Production:**
1. Complete OAuth verification process
2. Submit app untuk review Google

---

### Token Expired / Invalid

**Penyebab:** Token Google Drive kadaluarsa

**Solusi:**
1. Hapus file: `storage/app/google-drive/token.json`
2. Authorize ulang dari: `/admin/google-drive/authorize`

---

### Files Not Uploading

**Check:**
1. `.env` → `GOOGLE_DRIVE_ENABLED=true`
2. Token valid (ada file `token.json`)
3. Check logs: `storage/logs/laravel.log`

**Test manual:**
```bash
# Check token file exists
ls storage/app/google-drive/token.json

# Check permissions
chmod -R 775 storage/
```

---

### Error: "Quota exceeded"

**Penyebab:** Limit API Google Drive tercapai

**Info:**
- Free tier: 1 billion queries/day
- Default project: 20,000 queries/100 seconds/user

**Solusi:**
1. Tunggu reset quota (biasanya midnight PST)
2. Atau request quota increase di Google Cloud Console
3. Untuk high-volume: consider service account instead of OAuth

---

## Security Best Practices

### 1. Protect Credentials

```bash
# Add to .gitignore (sudah default Laravel)
.env
storage/app/google-drive/credentials.json
storage/app/google-drive/token.json
```

### 2. Use HTTPS in Production

Untuk production, pastikan:
- SSL certificate installed
- Force HTTPS di Laravel
- Update redirect URI ke `https://`

### 3. Limit API Scopes

Gunakan scope minimal yang dibutuhkan:
- ✅ `drive.file` - akses hanya file yang dibuat app
- ❌ `drive` - akses semua file di Drive (too broad)

### 4. Regular Token Rotation

Token refresh otomatis oleh library, tapi:
- Monitor expired tokens
- Implement re-authorization flow

---

## Advanced Configuration

### Using Service Account (Alternative)

Untuk production dengan auto-backup, consider service account:

**Advantages:**
- No manual authorization needed
- Better for server-to-server
- No token expiration issues

**Steps:**
1. Create Service Account di Google Cloud
2. Download JSON key file
3. Update `GoogleDriveService` untuk use service account
4. Share root folder dengan service account email

### Custom Folder Structure

Edit di `config/google-drive.php`:

```php
'folder_structure' => [
    'root' => env('GOOGLE_DRIVE_ROOT_FOLDER', 'Documents'),
    'pattern' => '{jenjang}/{tahun}/{nim}', // Custom structure
],
```

### Backup Schedule (Cron)

Setup automatic backup via Laravel scheduler:

**app/Console/Kernel.php:**
```php
protected function schedule(Schedule $schedule)
{
    // Backup semua dokumen setiap hari jam 2 pagi
    $schedule->command('backup:google-drive')->dailyAt('02:00');
}
```

**Create command:**
```bash
php artisan make:command BackupToGoogleDrive
```

---

## API Rate Limits

Google Drive API Limits:
- **Queries per day:** 1,000,000,000 (1 billion) - plenty
- **Queries per 100 seconds per user:** 1,000
- **Queries per 100 seconds:** 20,000

Untuk aplikasi ini (moderate usage):
- Upload 100 dokumen = ~200 requests
- Well within limits

---

## Support & Resources

### Official Documentation
- [Google Drive API v3](https://developers.google.com/drive/api/v3/about-sdk)
- [PHP Client Library](https://github.com/googleapis/google-api-php-client)
- [OAuth 2.0](https://developers.google.com/identity/protocols/oauth2)

### Useful Links
- [Google Cloud Console](https://console.cloud.google.com/)
- [API Explorer](https://developers.google.com/drive/api/v3/reference)
- [Quota Information](https://console.cloud.google.com/apis/api/drive.googleapis.com/quotas)

### Common Questions

**Q: Apakah gratis?**
A: Ya, Google Drive API gratis dengan generous quota. Storage mengikuti quota Google Drive account (15GB free).

**Q: Bisa pakai Google Workspace?**
A: Ya, lebih baik. Workspace punya unlimited storage dan higher quota.

**Q: Bagaimana jika file di local dihapus?**
A: Bisa restore dari Google Drive via fitur download.

**Q: Apakah auto-sync dua arah?**
A: Tidak. Ini one-way backup: Local → Drive. Local tetap source of truth.

**Q: Bisa share dokumen ke pihak ketiga?**
A: Ya, bisa implement sharing via Google Drive API.

---

## Next Steps

After successful setup:

1. ✅ Test all features thoroughly
2. ✅ Monitor logs for errors
3. ✅ Setup monitoring/alerts for failed backups
4. ✅ Document for your team
5. ✅ Consider backup schedule
6. ✅ Plan for disaster recovery

---

## Changelog

### Version 1.0 (Initial Release)
- OAuth 2.0 authentication
- Manual backup per document
- Bulk backup all documents
- Download from Google Drive
- Delete backup
- Statistics dashboard
- Auto-backup on upload

---

**Last Updated:** March 10, 2026
**Author:** Portal Mahasiswa Development Team
