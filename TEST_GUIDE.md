# Test Guide - Dynamic Data Input

## Prerequisites
✅ Server Laravel running: `php artisan serve`
✅ Database migration completed
✅ Database seeder completed (93 records)

## Test URLs
- **Sarjana (D3/D4/S1)**: http://localhost:8000/sarjana
- **Magister (S2)**: http://localhost:8000/magister
- **Doktoral (S3)**: http://localhost:8000/doktoral

---

## Test Case 1: Sarjana (WNI)

### 1. Buka Form
```
http://localhost:8000/sarjana
```

### 2. Isi Data
```
Nama Lengkap: Budi Santoso
Tempat Lahir: Bandung
Tanggal Lahir: 2003-05-15
Jenis Kelamin: Laki-laki
Nama Ibu Kandung: Siti Aminah
Agama: Islam
Alamat: Jl. Raya Bandung No. 123
Kewarganegaraan: WNI
NIK: 3201031234567890 (16 digit, unique)
Jalur Program: RPL
Jenjang: S1
Program Studi: Teknik Informatika
No HP: 081234567890
Email: budi.santoso@test.com (unique)
Password: password123
Konfirmasi Password: password123
CAPTCHA: [jawab sesuai soal]
```

### 3. Submit & Verify
```bash
# Check database via tinker
php artisan tinker

# Cari data yang baru saja diinput
Mahasiswa::where('email', 'budi.santoso@test.com')->first();

# Atau lihat data terbaru
Mahasiswa::latest()->first();

# Expected output:
# - nama_lengkap: "Budi Santoso"
# - jenis_pendaftaran: "sarjana"
# - status_verifikasi: "pending"
# - password: hashed (bukan plaintext)
# - tanggal_daftar: [timestamp sekarang]
```

---

## Test Case 2: Sarjana (WNA)

### 1. Buka Form
```
http://localhost:8000/sarjana
```

### 2. Isi Data
```
Nama Lengkap: John Smith
Tempat Lahir: New York
Tanggal Lahir: 2002-08-20
Jenis Kelamin: Laki-laki
Nama Ibu Kandung: Mary Smith
Agama: Protestan
Alamat: 123 Main Street
Kewarganegaraan: WNA
Negara: United States
Passport: AB1234567 (unique)
Jalur Program: Non RPL
Jenjang: S1
Program Studi: International Business
No HP: 081298765432
Email: john.smith@test.com (unique)
Password: password123
Konfirmasi Password: password123
CAPTCHA: [jawab sesuai soal]
```

### 3. Verify
```php
Mahasiswa::where('email', 'john.smith@test.com')->first();

# Expected:
# - kewarganegaraan: "WNA"
# - negara: "United States"
# - passport: "AB1234567"
# - nik: NULL (karena WNA)
```

---

## Test Case 3: Magister (S2)

### 1. Buka Form
```
http://localhost:8000/magister
```

### 2. Isi Data
```
Nama Lengkap: Siti Rahayu
Tempat Lahir: Surabaya
Tanggal Lahir: 1995-03-10
Jenis Kelamin: Perempuan
Status Perkawinan: Kawin
Agama: Islam
NIK: 3578031234567891 (unique)
Nama Ibu Kandung: Fatimah
Kewarganegaraan: WNI
No HP: 081234567891
Email: siti.rahayu@test.com (unique)
Password: password123
Konfirmasi Password: password123
CAPTCHA: [jawab sesuai soal]
```

### 3. Verify
```php
Mahasiswa::where('email', 'siti.rahayu@test.com')->first();

# Expected:
# - jenis_pendaftaran: "magister"
# - status_kawin: "Kawin"
# - jalur_program: NULL (karena Magister tidak ada field ini)
```

---

## Test Case 4: Doktoral (S3)

### 1. Buka Form
```
http://localhost:8000/doktoral
```

### 2. Isi Data
```
Nama Lengkap: Dr. Ahmad Hidayat
Tempat Lahir: Yogyakarta
Tanggal Lahir: 1988-11-25
Jenis Kelamin: Laki-laki
Status Perkawinan: Kawin
Agama: Islam
NIK: 3471112234567892 (unique)
Nama Ibu Kandung: Aminah
Kewarganegaraan: WNI
No HP: 081234567892
Email: ahmad.hidayat@test.com (unique)
Password: password123
Konfirmasi Password: password123
CAPTCHA: [jawab sesuai soal]
```

### 3. Verify
```php
Mahasiswa::where('email', 'ahmad.hidayat@test.com')->first();

# Expected:
# - jenis_pendaftaran: "doktoral"
# - status_kawin: "Kawin"
```

---

## Test Case 5: Validation Error - Duplicate Email

### 1. Submit dengan email yang sudah ada
```
Email: budi.santoso@test.com (sudah digunakan di Test Case 1)
```

### 2. Expected Response
```json
{
    "errors": {
        "email": ["Email sudah terdaftar, gunakan email lain."]
    }
}
```

---

## Test Case 6: Validation Error - Duplicate NIK

### 1. Submit dengan NIK yang sudah ada
```
NIK: 3201031234567890 (sudah digunakan di Test Case 1)
```

### 2. Expected Response
```json
{
    "errors": {
        "nik": ["NIK sudah terdaftar."]
    }
}
```

---

## Test Case 7: Validation Error - CAPTCHA Salah

### 1. Input CAPTCHA yang salah
```
CAPTCHA soal: 5 + 3 = ?
Input: 9 (salah, seharusnya 8)
```

### 2. Expected Response
```json
{
    "errors": {
        "captcha_answer": ["Jawaban captcha salah. Silakan coba lagi."]
    }
}
```

---

## Test Case 8: CAPTCHA Refresh

### 1. Click tombol refresh CAPTCHA
```
Button: 🔄 (di sebelah input CAPTCHA)
```

### 2. Expected Behavior
- ✅ Soal CAPTCHA berubah tanpa reload halaman
- ✅ Session di server di-update
- ✅ User bisa jawab soal baru

---

## Test Case 9: Age Validation (Usia < 15 tahun)

### 1. Input tanggal lahir terlalu muda
```
Tanggal Lahir: 2015-01-01 (usia 9 tahun)
```

### 2. Expected Response
```json
{
    "errors": {
        "tanggal_lahir": ["Usia minimal pendaftar adalah 15 tahun. Usia Anda saat ini: 9 tahun."]
    }
}
```

---

## Test Case 10: Password Confirmation Mismatch

### 1. Input password tidak cocok
```
Password: password123
Konfirmasi Password: password456
```

### 2. Expected Response
```json
{
    "errors": {
        "password": ["Konfirmasi password tidak cocok."]
    }
}
```

---

## Verification Commands

### Check Total Records
```php
php artisan tinker

// Total mahasiswa
Mahasiswa::count();

// Per jenis pendaftaran
Mahasiswa::sarjana()->count();
Mahasiswa::magister()->count();
Mahasiswa::doktoral()->count();

// Per status
Mahasiswa::pending()->count();
Mahasiswa::verified()->count();
```

### Check Latest Entry
```php
$latest = Mahasiswa::latest()->first();
echo "Nama: " . $latest->nama_lengkap;
echo "Email: " . $latest->email;
echo "Jenis: " . $latest->jenis_pendaftaran;
echo "Status: " . $latest->status_verifikasi;
```

### Check Password Hashing
```php
$mahasiswa = Mahasiswa::where('email', 'budi.santoso@test.com')->first();

// Password harus di-hash (bukan plaintext)
echo $mahasiswa->password; // $2y$10$...

// Verify password
Hash::check('password123', $mahasiswa->password); // true
```

### Check Unique Constraints
```php
// Cek duplikasi email
Mahasiswa::where('email', 'budi.santoso@test.com')->count(); // should be 1

// Cek duplikasi NIK
Mahasiswa::where('nik', '3201031234567890')->count(); // should be 1

// Cek duplikasi passport
Mahasiswa::where('passport', 'AB1234567')->count(); // should be 1
```

---

## Expected Database State After All Tests

```php
// Total records: 93 (seeder) + 4 (manual test) = 97
Mahasiswa::count(); // 97

// Sarjana: 47 + 2 = 49
Mahasiswa::sarjana()->count(); // 49

// Magister: 31 + 1 = 32
Mahasiswa::magister()->count(); // 32

// Doktoral: 15 + 1 = 16
Mahasiswa::doktoral()->count(); // 16

// All pending (seeder + manual)
Mahasiswa::pending()->count(); // 97
```

---

## Success Criteria

### ✅ Form Submission
- [x] Form dapat submit tanpa error
- [x] Success message muncul
- [x] Form di-reset setelah submit

### ✅ Data Integrity
- [x] Semua field dari form masuk ke database
- [x] Password di-hash (bukan plaintext)
- [x] jenis_pendaftaran sesuai (sarjana/magister/doktoral)
- [x] status_verifikasi = 'pending'
- [x] tanggal_daftar = current timestamp

### ✅ Validation
- [x] Email unique validation
- [x] NIK unique validation (WNI)
- [x] Passport unique validation (WNA)
- [x] Age validation (min 15 tahun)
- [x] Password confirmation
- [x] CAPTCHA validation

### ✅ Conditional Logic
- [x] WNI: NIK required, negara & passport NULL
- [x] WNA: passport & negara required, NIK NULL
- [x] Sarjana: jalur_program, jenjang, program_studi required
- [x] Magister/Doktoral: status_kawin required

---

## Troubleshooting

### Problem: Form tidak submit
**Check**: 
1. Server running? `php artisan serve`
2. CSRF token? Check `{{ csrf_token() }}` di form
3. Route exists? `php artisan route:list | grep register`

### Problem: Data tidak masuk database
**Check**:
1. Migration? `php artisan migrate:status`
2. Fillable? Check `$fillable` di Model
3. Database connection? Check `.env` DB_*

### Problem: Validation error tidak muncul
**Check**:
1. JavaScript? Check browser console
2. AJAX error handler? Check code
3. Response format? Check controller return

---

## Quick Verification Script

Save as `verify_dynamic_input.php`:

```php
<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Mahasiswa;

echo "=== VERIFIKASI DYNAMIC DATA INPUT ===\n\n";

// 1. Total records
$total = Mahasiswa::count();
echo "1. Total Mahasiswa: {$total}\n";

// 2. Per jenis
$sarjana = Mahasiswa::sarjana()->count();
$magister = Mahasiswa::magister()->count();
$doktoral = Mahasiswa::doktoral()->count();
echo "2. Sarjana: {$sarjana}, Magister: {$magister}, Doktoral: {$doktoral}\n";

// 3. Latest entry
$latest = Mahasiswa::latest()->first();
if ($latest) {
    echo "3. Data Terakhir:\n";
    echo "   - Nama: {$latest->nama_lengkap}\n";
    echo "   - Email: {$latest->email}\n";
    echo "   - Jenis: {$latest->jenis_pendaftaran}\n";
    echo "   - Status: {$latest->status_verifikasi}\n";
    echo "   - Password Hashed: " . (strlen($latest->password) > 20 ? 'YES' : 'NO') . "\n";
}

// 4. WNI vs WNA
$wni = Mahasiswa::where('kewarganegaraan', 'WNI')->count();
$wna = Mahasiswa::where('kewarganegaraan', 'WNA')->count();
echo "4. WNI: {$wni}, WNA: {$wna}\n";

// 5. Status
$pending = Mahasiswa::pending()->count();
$verified = Mahasiswa::verified()->count();
echo "5. Pending: {$pending}, Verified: {$verified}\n";

echo "\n✅ VERIFIKASI SELESAI\n";
```

Run:
```bash
php verify_dynamic_input.php
```

---

## Summary

✅ **10 Test Cases** ready to verify dynamic data input
✅ **Validation tested**: email, NIK, passport, age, password, CAPTCHA
✅ **Conditional logic**: WNI vs WNA, Sarjana vs Magister vs Doktoral
✅ **Database integrity**: unique constraints, password hashing, timestamps

**Next Steps**:
1. Test via browser: http://localhost:8000/sarjana
2. Check database: `php artisan tinker` → `Mahasiswa::latest()->first()`
3. Run verification: `php verify_dynamic_input.php`
