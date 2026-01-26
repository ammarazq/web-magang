# Panduan Upload Dokumen D3, D4, dan S1

## Deskripsi
Fitur upload dokumen untuk mahasiswa program Diploma III (D3), Diploma IV (D4), dan Sarjana (S1) baik jalur reguler (Non RPL) maupun jalur RPL (Rekognisi Pembelajaran Lampau).

## Fitur Utama

### 1. Upload Dokumen D3 (Diploma III)
**URL:** `/sarjana/upload` (otomatis mendeteksi jenjang D3)

**Dokumen yang diperlukan:**
- ✅ Formulir Pendaftaran (JPG/PDF, max 2MB) - WAJIB
- ✅ Formulir Keabsahan Dokumen (JPG/PDF, max 2MB) - WAJIB
- ✅ Foto Formal 3x4 (JPG, max 1MB) - WAJIB
- ✅ KTP (JPG, max 1MB) - WAJIB
- ✅ FC Ijazah SLTA Legalisir (PDF, max 2MB) - WAJIB

**Total:** 5 dokumen wajib

### 2. Upload Dokumen D4 (Diploma IV / Sarjana Terapan)
**URL:** `/sarjana/upload` (otomatis mendeteksi jenjang D4)

**Dokumen yang diperlukan:**
- ✅ Formulir Pendaftaran (JPG/PDF, max 2MB) - WAJIB
- ✅ Formulir Keabsahan Dokumen (JPG/PDF, max 2MB) - WAJIB
- ✅ Foto Formal 3x4 (JPG, max 1MB) - WAJIB
- ✅ KTP (JPG, max 1MB) - WAJIB
- ✅ FC Ijazah SLTA Legalisir (PDF, max 2MB) - WAJIB

**Total:** 5 dokumen wajib

### 3. Upload Dokumen S1 Non RPL (Sarjana Reguler)
**URL:** `/sarjana/upload` (otomatis mendeteksi jenjang S1 Non RPL)

**Dokumen yang diperlukan:**
- ✅ Formulir Pendaftaran (JPG/PDF, max 2MB) - WAJIB
- ✅ Formulir Keabsahan Dokumen (JPG/PDF, max 2MB) - WAJIB
- ✅ Foto Formal 3x4 (JPG, max 1MB) - WAJIB
- ✅ KTP (JPG, max 1MB) - WAJIB
- ✅ FC Ijazah SLTA Legalisir (PDF, max 2MB) - WAJIB

**Total:** 5 dokumen wajib

### 4. Upload Dokumen S1 RPL (Rekognisi Pembelajaran Lampau)
**URL:** `/sarjana/upload` (otomatis mendeteksi jenjang S1 RPL)

**Dokumen Umum (Wajib):**
- ✅ Formulir Pendaftaran (JPG/PDF, max 2MB)
- ✅ Formulir Keabsahan Dokumen (JPG/PDF, max 2MB)
- ✅ Foto Formal 3x4 (JPG, max 1MB)
- ✅ KTP (JPG, max 1MB)

**Dokumen Khusus RPL (Wajib):**
- ✅ Ijazah SLTA Asli (PDF, max 2MB)
- ✅ Transkrip Nilai D3/D4/S1 (PDF, max 2MB)
- ✅ FC Ijazah D3/D4/S1 Legalisir (PDF, max 2MB)

**Total:** 8 dokumen wajib (4 umum + 3 khusus RPL)

## Cara Penggunaan

### Untuk Mahasiswa:

1. **Login** menggunakan akun yang sudah didaftarkan
2. **Akses halaman upload:** Kunjungi `/sarjana/upload`
   - Sistem akan otomatis menampilkan form sesuai jenjang (D3/D4/S1)
   - Untuk S1, form akan otomatis menyesuaikan dengan jalur (RPL/Non RPL)
3. **Pilih file** untuk setiap dokumen yang akan diupload
4. **Klik tombol "Upload Dokumen"** untuk menyimpan
5. **Status upload** akan ditampilkan:
   - ✅ Sudah Diupload (kotak hijau)
   - ⚠️ Belum Diupload (kotak putih dengan border putus-putus)

### Fitur Halaman Upload:

- **Informasi Mahasiswa:** Ditampilkan di sidebar kiri
- **Progress Bar:** Menunjukkan persentase kelengkapan dokumen
- **Status Dokumen:** Menunjukkan status kelengkapan dokumen
- **Upload Multiple Files:** Dapat mengupload beberapa dokumen sekaligus
- **Update File:** File yang sudah diupload dapat diganti dengan yang baru
- **Indikator Visual:**
  - Badge WAJIB (merah) untuk dokumen yang harus diupload
  - Badge SUDAH DIUPLOAD (hijau) untuk dokumen yang telah diupload
  - Kotak hijau untuk dokumen yang sudah diupload
  - Kotak putih untuk dokumen yang belum diupload

## Routes yang Tersedia

### Sarjana (D3/D4/S1):
```php
Route::get('/sarjana/upload', [SarjanaController::class, 'uploadForm'])->name('sarjana.upload');
Route::post('/sarjana/upload', [SarjanaController::class, 'uploadDokumen'])->name('sarjana.upload.submit');
```

## Controller Methods

### SarjanaController:
- `uploadForm()` - Menampilkan form upload dokumen D3/D4/S1 (auto-detect jenjang)
- `uploadDokumen(Request $request)` - Memproses upload dokumen D3/D4/S1

**Smart Routing:**
Controller akan otomatis mengarahkan ke view yang sesuai berdasarkan jenjang:
- D3 → `pages.d3-upload`
- D4 → `pages.d4-upload`
- S1 → `pages.s1-upload` (menampilkan form sesuai jalur RPL/Non RPL)

## Views

### Diploma III:
- **File:** `resources/views/pages/d3-upload.blade.php`
- **Layout:** Bootstrap 5
- **Icons:** Font Awesome 6.4.0

### Diploma IV:
- **File:** `resources/views/pages/d4-upload.blade.php`
- **Layout:** Bootstrap 5
- **Icons:** Font Awesome 6.4.0

### Sarjana (S1):
- **File:** `resources/views/pages/s1-upload.blade.php`
- **Layout:** Bootstrap 5
- **Icons:** Font Awesome 6.4.0
- **Smart Form:** Menampilkan field tambahan untuk RPL secara otomatis

## Penyimpanan File

File yang diupload akan disimpan di:
```
storage/app/public/dokumen_mahasiswa/
```

Format nama file:
```
{mahasiswa_id}_{field_name}_{timestamp}.{extension}
```

Contoh:
```
1_formulir_pendaftaran_1738035600.pdf
1_foto_formal_1738035600.jpg
1_transkrip_nilai_1738035600.pdf
```

## Validasi

### Format File:
- **Gambar:** JPG, JPEG
- **Dokumen:** PDF

### Ukuran File:
- **Foto:** Max 1MB
- **Dokumen PDF:** Max 2MB

### Pesan Error:
- Format file tidak sesuai
- Ukuran file melebihi batas
- File gagal diupload

## Keamanan

1. **Authentication Required:** Hanya mahasiswa yang sudah login yang dapat mengakses
2. **File Validation:** Validasi format dan ukuran file
3. **Mahasiswa Ownership:** Hanya dapat mengupload dokumen untuk akun sendiri
4. **Storage Security:** File disimpan di storage folder yang tidak dapat diakses langsung
5. **Auto-detect Jenjang:** Sistem otomatis mendeteksi jenjang dan jalur program

## Perbedaan Antar Jenjang

### D3 vs D4 vs S1 Non RPL
Ketiga jenjang ini memiliki persyaratan dokumen yang sama:
- 5 dokumen wajib yang sama
- Tidak ada dokumen tambahan

### S1 RPL (Khusus)
Memiliki persyaratan tambahan:
- 4 dokumen umum (sama dengan reguler, minus ijazah SLTA)
- 3 dokumen khusus RPL (ijazah SLTA asli, transkrip, ijazah D3/D4/S1)
- Total 8 dokumen wajib

## Status Dokumen

Status dokumen pada tabel `dokumen_mahasiswa`:
- `belum_lengkap` - Dokumen belum lengkap
- `lengkap` - Dokumen sudah lengkap, menunggu verifikasi
- `diverifikasi` - Dokumen sudah diverifikasi admin
- `ditolak` - Dokumen ditolak admin

## Progress Bar

Setiap jenjang memiliki progress bar yang menampilkan:
- Jumlah dokumen yang sudah diupload vs total dokumen wajib
- Persentase kelengkapan
- Warna:
  - Kuning: Belum lengkap (< 100%)
  - Hijau: Lengkap (100%)

## Catatan Penting

1. **Satu URL untuk Semua:** Route `/sarjana/upload` otomatis mendeteksi jenjang
2. **Smart Form:** Form menyesuaikan dengan jenjang dan jalur program
3. **Upload Bertahap:** Dapat mengupload dokumen satu per satu atau sekaligus
4. **Update dokumen:** Upload ulang akan mengganti dokumen lama
5. **File name berbeda:** Setiap upload akan menghasilkan nama file dengan timestamp baru
6. **Backup file lama:** File lama tidak otomatis terhapus (perlu cleanup manual jika diperlukan)

## Troubleshooting

### Error "Data mahasiswa tidak ditemukan"
**Solusi:** Pastikan sudah login dengan akun yang terdaftar sebagai mahasiswa

### Error "Halaman upload untuk jenjang X belum tersedia"
**Solusi:** View untuk jenjang tersebut belum dibuat. Hubungi administrator.

### Error "File terlalu besar"
**Solusi:** Kompres atau resize file agar ukurannya tidak melebihi batas

### Error "Format file tidak didukung"
**Solusi:** Pastikan file sesuai dengan format yang diminta (JPG/PDF)

### Dokumen tidak muncul setelah upload
**Solusi:** Refresh halaman atau cek notifikasi success/error

## Perbandingan dengan Jenjang Lain

### Ringkasan Semua Jenjang:

| Jenjang | Dokumen Wajib | Dokumen Opsional | Total |
|---------|---------------|------------------|-------|
| D3 | 5 | 0 | 5 |
| D4 | 5 | 0 | 5 |
| S1 Non RPL | 5 | 0 | 5 |
| S1 RPL | 8 | 0 | 8 |
| S2 (Magister) | 9 | 5 | 14 |
| S3 (Doktoral) | 12 | 5 | 17 |

## Developer Notes

### Controller Logic:
```php
// Auto-detect jenjang
$jenjang = strtolower($mahasiswa->jenjang); // 'd3', 'd4', 's1'
$viewName = 'pages.' . $jenjang . '-upload';

// Check view exists
if (!view()->exists($viewName)) {
    return redirect()->route('mahasiswa.dashboard')
        ->with('error', 'Halaman upload untuk jenjang belum tersedia');
}
```

### Extend Functionality:
1. Add automatic file cleanup for old uploads
2. Add file preview modal
3. Add drag-and-drop upload
4. Add bulk download for admin
5. Add document verification workflow
6. Add email notification on upload

### Database Structure:
Menggunakan tabel `dokumen_mahasiswa` dengan:
- `mahasiswa_id` → foreign key ke tabel `mahasiswa`
- Field untuk setiap jenis dokumen
- Timestamps untuk tracking
- Status verifikasi
