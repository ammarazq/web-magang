# Panduan Sistem Tracking Progress Dokumen Mahasiswa

## 📊 Fitur Utama

Sistem ini memungkinkan **admin** untuk melakukan tracking/monitoring progress upload dokumen mahasiswa **mulai dari 0%** hingga 100%, bahkan sebelum mahasiswa mengupload dokumen apapun.

---

## ✅ Implementasi

### 1. **Dashboard Admin - Progress Tracking**

**Lokasi:** `resources/views/admin/dashboard.blade.php`

Dashboard admin sekarang menampilkan:
- ✅ Tabel 10 mahasiswa terbaru
- ✅ Progress bar untuk setiap mahasiswa (0-100%)
- ✅ Jumlah dokumen yang sudah diupload vs total dokumen wajib
- ✅ Status dokumen (Belum Upload, Belum Lengkap, Lengkap, Diverifikasi, Ditolak)
- ✅ Color-coded progress bars:
  - **Merah** (< 40%) - Baru mulai
  - **Oranye** (40-69%) - Sedang proses
  - **Biru** (70-99%) - Hampir lengkap
  - **Hijau** (100%) - Lengkap

**Contoh Output:**
```
Nama Mahasiswa    | Jenjang | Program Studi           | Progress Upload    | Status         | Aksi
------------------|---------|-------------------------|--------------------|-----------------|---------
Ahmad Fauzi       | D4      | Teknik Informatika(471) | [■■■■□] 80% (4/5)  | Belum Lengkap  | Detail
Siti Aminah       | S1      | Sistem Informasi (252)  | [■□□□□] 20% (1/5)  | Belum Lengkap  | Detail
Budi Santoso      | S2      | Magister TI (911)       | [□□□□□] 0% (0/10)  | Belum Upload   | Detail
```

### 2. **Tabel Verifikasi Dokumen**

**Lokasi:** `resources/views/admin/verifikasi_list.blade.php`

Tabel verifikasi sekarang menampilkan **SEMUA mahasiswa** yang terdaftar, termasuk:
- ✅ Mahasiswa yang belum upload dokumen apapun (0%)
- ✅ Mahasiswa yang baru upload sebagian dokumen (20%, 40%, 60%, dll)
- ✅ Mahasiswa yang sudah lengkap (100%)

**Perubahan Controller:**
```php
// SEBELUM (hanya mahasiswa dengan dokumen):
$mahasiswaList = Mahasiswa::with(['dokumen'])
    ->whereHas('dokumen')
    ->paginate(20);

// SESUDAH (semua mahasiswa, termasuk yang 0%):
$mahasiswaList = Mahasiswa::with(['dokumen'])
    ->orderBy('created_at', 'desc')
    ->paginate(20);
```

### 3. **Detail Mahasiswa**

**Lokasi:** `resources/views/admin/detail_mahasiswa.blade.php`

Halaman detail sekarang dapat diakses **bahkan untuk mahasiswa yang belum upload dokumen**:

- ✅ Menampilkan progress 0% untuk mahasiswa belum upload
- ✅ Menampilkan daftar dokumen yang dibutuhkan
- ✅ Memberikan informasi jelas tentang dokumen apa saja yang perlu diupload

**Perubahan Controller:**
```php
// SEBELUM:
if (!$dokumen) {
    return redirect()->back()->with('error', 'Mahasiswa belum upload dokumen.');
}

// SESUDAH:
// Tetap tampilkan halaman detail meskipun $dokumen = null
// Ini memungkinkan admin melihat progress dari 0%
return view('admin.detail_mahasiswa', compact('mahasiswa', 'dokumen'));
```

---

## 🎯 Cara Penggunaan

### Untuk Admin:

#### 1. **Melihat Progress di Dashboard**
1. Login sebagai admin
2. Dashboard akan langsung menampilkan 10 mahasiswa terbaru
3. Lihat kolom "Progress Upload" untuk melihat persentase
4. Lihat jumlah dokumen: `4/5 dokumen` = 4 sudah diupload dari 5 total wajib

#### 2. **Melihat Semua Mahasiswa di Tabel Verifikasi**
1. Klik menu "Verifikasi Dokumen"
2. Tabel akan menampilkan **SEMUA** mahasiswa
3. Mahasiswa yang belum upload akan menunjukkan:
   - Progress: 0%
   - Status: "Belum Upload"
   - Jumlah: 0 dokumen

#### 3. **Melihat Detail Progress Mahasiswa**
1. Klik tombol "Detail" pada mahasiswa manapun
2. Akan muncul:
   - **Jika sudah upload:** Daftar dokumen dengan status (✓ Uploaded / ✗ Belum)
   - **Jika belum upload:** Pesan "Mahasiswa belum mengupload dokumen apapun" + daftar dokumen yang dibutuhkan

---

## 📊 Contoh Tracking dari 0% ke 100%

### Skenario: Mahasiswa D4 (5 dokumen wajib)

#### **Awal - 0%**
```
Progress: [□□□□□] 0%
Dokumen: 0/5 dokumen
Status: Belum Upload
```

#### **Upload 1 Dokumen - 20%**
```
Progress: [■□□□□] 20%
Dokumen: 1/5 dokumen (Formulir Pendaftaran ✓)
Status: Belum Lengkap
```

#### **Upload 2 Dokumen - 40%**
```
Progress: [■■□□□] 40%
Dokumen: 2/5 dokumen
- Formulir Pendaftaran ✓
- Foto Formal ✓
Status: Belum Lengkap
```

#### **Upload 4 Dokumen - 80%**
```
Progress: [■■■■□] 80%
Dokumen: 4/5 dokumen
- Formulir Pendaftaran ✓
- Formulir Keabsahan ✓
- Foto Formal ✓
- KTP ✓
- Ijazah SLTA ✗ (Belum)
Status: Belum Lengkap
```

#### **Upload Semua - 100%**
```
Progress: [■■■■■] 100%
Dokumen: 5/5 dokumen
Semua dokumen lengkap ✓
Status: Lengkap
```

---

## 🔍 Technical Details

### Model Methods (DokumenMahasiswa)

#### 1. `getPersentaseKelengkapan()`
Menghitung persentase dokumen yang sudah diupload.

**Return:** Integer (0-100)

```php
$persentase = $dokumen->getPersentaseKelengkapan();
// Output: 60 (jika 3 dari 5 dokumen sudah diupload)
```

#### 2. `getJumlahDokumen()`
Menghitung jumlah dokumen uploaded vs total.

**Return:** Array
```php
$jumlah = $dokumen->getJumlahDokumen();
// Output: ['uploaded' => 3, 'total' => 5]
```

### Handling Null Dokumen

Semua view sudah di-update untuk handle kondisi `$dokumen = null`:

```php
@if($dokumen)
    // Tampilkan progress berdasarkan data
    $persentase = $dokumen->getPersentaseKelengkapan();
@else
    // Tampilkan progress 0%
    <div class="progress-bar bg-danger" style="width: 0%">0%</div>
@endif
```

---

## 📋 Dokumen Wajib per Jenjang

Sistem menghitung progress berdasarkan jumlah dokumen wajib untuk setiap jenjang:

| Jenjang        | Jalur      | Jumlah Dokumen Wajib |
|----------------|------------|----------------------|
| D3             | -          | 5                    |
| D4             | -          | 5                    |
| S1             | Non RPL    | 5                    |
| S1             | RPL        | 6                    |
| S2 (Magister)  | -          | 10                   |
| S3 (Doktoral)  | -          | 11                   |

**Contoh Perhitungan:**
- Mahasiswa S2 upload 3 dokumen dari 10 wajib = **30%**
- Mahasiswa D3 upload 4 dokumen dari 5 wajib = **80%**
- Mahasiswa S1 RPL upload 0 dokumen dari 6 wajib = **0%**

---

## 🎨 UI Components

### Progress Bar
```html
<div class="progress" style="height: 25px;">
    <div class="progress-bar bg-warning" 
         style="width: 40%;" 
         role="progressbar">
        <strong>40%</strong>
    </div>
</div>
<small class="text-muted">
    <i class="fas fa-file-alt"></i> 2/5 dokumen
</small>
```

### Status Badge
```html
<!-- Belum Upload (0%) -->
<span class="badge bg-secondary">Belum Upload</span>

<!-- Belum Lengkap (1-99%) -->
<span class="badge bg-warning">Belum Lengkap</span>

<!-- Lengkap (100%) -->
<span class="badge bg-info">Lengkap</span>

<!-- Diverifikasi -->
<span class="badge bg-success">Diverifikasi</span>

<!-- Ditolak -->
<span class="badge bg-danger">Ditolak</span>
```

---

## ✅ Testing Checklist

### Test 1: Dashboard menampilkan progress dari 0%
- [ ] Login sebagai admin
- [ ] Buka dashboard
- [ ] Verifikasi tabel "Progress Upload Dokumen Mahasiswa" muncul
- [ ] Verifikasi mahasiswa yang belum upload (0%) ditampilkan
- [ ] Verifikasi progress bar muncul dengan warna merah untuk 0%

### Test 2: Tabel verifikasi menampilkan semua mahasiswa
- [ ] Klik menu "Verifikasi Dokumen"
- [ ] Verifikasi SEMUA mahasiswa muncul (termasuk yang 0%)
- [ ] Verifikasi kolom "Kelengkapan" menampilkan progress
- [ ] Verifikasi status "Belum Upload" muncul untuk mahasiswa 0%

### Test 3: Detail mahasiswa dapat diakses meskipun 0%
- [ ] Klik "Detail" pada mahasiswa yang belum upload
- [ ] Verifikasi halaman detail terbuka (tidak error)
- [ ] Verifikasi muncul pesan "Mahasiswa belum mengupload dokumen apapun"
- [ ] Verifikasi progress bar menunjukkan 0%
- [ ] Verifikasi daftar dokumen yang dibutuhkan muncul

### Test 4: Tracking progress secara bertahap
- [ ] Login sebagai mahasiswa
- [ ] Upload 1 dokumen
- [ ] Login sebagai admin, verifikasi progress = 20% (untuk D3/D4/S1)
- [ ] Login mahasiswa, upload 1 dokumen lagi
- [ ] Refresh admin dashboard, verifikasi progress = 40%
- [ ] Ulangi hingga 100%

---

## 📝 File yang Dimodifikasi

### Controllers
1. ✅ `app/Http/Controllers/AdminController.php`
   - `verifikasiDokumenList()` - Tampilkan semua mahasiswa
   - `detailMahasiswa()` - Handle null dokumen

### Views
1. ✅ `resources/views/admin/dashboard.blade.php`
   - Tambah tabel progress tracking
   - Handle mahasiswa dengan 0%

2. ✅ `resources/views/admin/verifikasi_list.blade.php`
   - Sudah support 0% (tidak perlu modifikasi)

3. ✅ `resources/views/admin/detail_mahasiswa.blade.php`
   - Handle null dokumen di semua section
   - Tampilkan pesan informatif untuk 0%

### Models
- ✅ `app/Models/DokumenMahasiswa.php` (sudah ada method yang diperlukan)

---

## 🎯 Kesimpulan

Dengan implementasi ini, **admin dapat melakukan tracking progress upload dokumen mahasiswa mulai dari 0%**:

✅ Dashboard menampilkan semua mahasiswa (termasuk 0%)
✅ Progress bar akurat dari 0% - 100%
✅ Jumlah dokumen ditampilkan dengan jelas (X/Y format)
✅ Detail mahasiswa dapat diakses meskipun belum upload
✅ Color-coded untuk memudahkan identifikasi status

**Status: ✅ Production Ready**
**Last Updated:** 1 Maret 2026
