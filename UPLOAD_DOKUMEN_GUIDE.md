# Panduan Upload Dokumen S2 (Magister) dan S3 (Doktoral)

## Deskripsi
Fitur upload dokumen ini memungkinkan mahasiswa program Magister (S2) dan Doktoral (S3) untuk mengupload dokumen persyaratan pendaftaran mereka secara online.

## Fitur Utama

### 1. Upload Dokumen Magister (S2)
**URL:** `/magister/upload`

**Dokumen yang diperlukan:**

#### Dokumen Umum (Wajib):
- Formulir Pendaftaran (JPG/PDF, max 2MB)
- Formulir Keabsahan Dokumen (JPG/PDF, max 2MB)
- Foto Formal 3x4 (JPG, max 1MB)
- KTP (JPG, max 1MB)
- FC Ijazah SLTA Legalisir (PDF, max 2MB)

#### Dokumen Khusus S2 (Wajib):
- Sertifikat Akreditasi Prodi D4/S1 (PDF, max 2MB)
- FC Transkrip Nilai D4/S1 Legalisir (PDF, max 2MB)
- Sertifikat TOEFL min. 450 (PDF, max 2MB)
- Rancangan Penelitian Singkat (PDF, max 2MB)

#### Dokumen Opsional:
- SK Mampu Menggunakan Komputer (PDF, max 2MB)
- Bukti Tes Potensi Akademik (PDF, max 2MB)
- Bukti Seleksi Tes Substansi (PDF, max 2MB)
- Formulir Isian Foto (JPG/PDF, max 2MB)
- Daftar Riwayat Hidup/CV (PDF, max 2MB)

### 2. Upload Dokumen Doktoral (S3)
**URL:** `/doktoral/upload`

**Dokumen yang diperlukan:**

#### Dokumen Umum (Wajib):
- Formulir Pendaftaran (JPG/PDF, max 2MB)
- Formulir Keabsahan Dokumen (JPG/PDF, max 2MB)
- Foto Formal 3x4 (JPG, max 1MB)
- KTP (JPG, max 1MB)
- FC Ijazah SLTA Legalisir (PDF, max 2MB)

#### Dokumen S2 - Persyaratan S3 (Wajib):
- FC Ijazah S2 Legalisir (PDF, max 2MB)
- FC Transkrip Nilai S2 Legalisir (PDF, max 2MB)
- Sertifikat Akreditasi Prodi S2 (PDF, max 2MB)

#### Dokumen Khusus S3 (Wajib):
- Sertifikat TOEFL min. 500 (PDF, max 2MB)
- Proposal Penelitian/Disertasi (PDF, max 2MB)

#### Dokumen Opsional:
- Sertifikat Akreditasi Prodi S1 (PDF, max 2MB)
- FC Transkrip Nilai S1 Legalisir (PDF, max 2MB)
- SK Mampu Menggunakan Komputer (PDF, max 2MB)
- Bukti Tes Potensi Akademik (PDF, max 2MB)
- Bukti Seleksi Tes Substansi (PDF, max 2MB)
- Formulir Isian Foto (JPG/PDF, max 2MB)
- Daftar Riwayat Hidup/CV (PDF, max 2MB)

## Cara Penggunaan

### Untuk Mahasiswa:

1. **Login** menggunakan akun yang sudah didaftarkan
2. **Akses halaman upload:**
   - Untuk S2: Kunjungi `/magister/upload`
   - Untuk S3: Kunjungi `/doktoral/upload`
3. **Pilih file** untuk setiap dokumen yang akan diupload
4. **Klik tombol "Upload Dokumen"** untuk menyimpan
5. **Status upload** akan ditampilkan:
   - ✅ Sudah Diupload (kotak hijau)
   - ⚠️ Belum Diupload (kotak putih dengan border putus-putus)

### Fitur Halaman Upload:

- **Informasi Mahasiswa:** Ditampilkan di sidebar kiri
- **Status Dokumen:** Menunjukkan status kelengkapan dokumen
- **Upload Multiple Files:** Dapat mengupload beberapa dokumen sekaligus
- **Update File:** File yang sudah diupload dapat diganti dengan yang baru
- **Indikator Visual:**
  - Badge WAJIB (merah) untuk dokumen yang harus diupload
  - Badge SUDAH DIUPLOAD (hijau) untuk dokumen yang telah diupload
  - Kotak hijau untuk dokumen yang sudah diupload
  - Kotak putih untuk dokumen yang belum diupload

## Routes yang Tersedia

### Magister (S2):
```php
Route::get('/magister/upload', [MagisterController::class, 'uploadForm'])->name('magister.upload');
Route::post('/magister/upload', [MagisterController::class, 'uploadDokumen'])->name('magister.upload.submit');
```

### Doktoral (S3):
```php
Route::get('/doktoral/upload', [DoktoralController::class, 'uploadForm'])->name('doktoral.upload');
Route::post('/doktoral/upload', [DoktoralController::class, 'uploadDokumen'])->name('doktoral.upload.submit');
```

## Controller Methods

### MagisterController:
- `uploadForm()` - Menampilkan form upload dokumen S2
- `uploadDokumen(Request $request)` - Memproses upload dokumen S2

### DoktoralController:
- `uploadForm()` - Menampilkan form upload dokumen S3
- `uploadDokumen(Request $request)` - Memproses upload dokumen S3

## Views

### Magister:
- **File:** `resources/views/pages/magister-upload.blade.php`
- **Layout:** Bootstrap 5
- **Icons:** Font Awesome 6.4.0

### Doktoral:
- **File:** `resources/views/pages/doktoral-upload.blade.php`
- **Layout:** Bootstrap 5
- **Icons:** Font Awesome 6.4.0

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
```

## Validasi

### Format File:
- **Gambar:** JPG, JPEG
- **Dokumen:** PDF
- **Formulir Foto:** JPG, JPEG, PDF

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

## Status Dokumen

Status dokumen pada tabel `dokumen_mahasiswa`:
- `belum_lengkap` - Dokumen belum lengkap
- `lengkap` - Dokumen sudah lengkap, menunggu verifikasi
- `diverifikasi` - Dokumen sudah diverifikasi admin
- `ditolak` - Dokumen ditolak admin

## Catatan Penting

1. **Satu kali upload:** Dapat mengupload beberapa dokumen sekaligus
2. **Update dokumen:** Upload ulang akan mengganti dokumen lama
3. **File name berbeda:** Setiap upload akan menghasilkan nama file dengan timestamp baru
4. **Backup file lama:** File lama tidak otomatis terhapus (perlu cleanup manual jika diperlukan)

## Troubleshooting

### Error "Data mahasiswa tidak ditemukan"
**Solusi:** Pastikan sudah login dengan akun yang terdaftar sebagai mahasiswa

### Error "File terlalu besar"
**Solusi:** Kompres atau resize file agar ukurannya tidak melebihi batas

### Error "Format file tidak didukung"
**Solusi:** Pastikan file sesuai dengan format yang diminta (JPG/PDF)

### Dokumen tidak muncul setelah upload
**Solusi:** Refresh halaman atau cek notifikasi success/error

## Developer Notes

### Extend Functionality:
1. Auto-delete old files when uploading new ones
2. Add file preview before upload
3. Add drag-and-drop functionality
4. Add progress bar for large files
5. Add batch download for admin

### Database Structure:
Tabel `dokumen_mahasiswa` memiliki kolom untuk semua jenis dokumen dengan relasi:
- `mahasiswa_id` -> foreign key ke tabel `mahasiswa`
- Timestamps untuk tracking
- Status verifikasi
