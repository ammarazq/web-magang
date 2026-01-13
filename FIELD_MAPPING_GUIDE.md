# MAPPING DATA: MAHASISWA → USERS

## 📋 STRUKTUR TABEL

### Tabel `users` (Standar Laravel)
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),                    -- Untuk nama pengguna
    email VARCHAR(255) UNIQUE,            -- Email untuk login
    email_verified_at TIMESTAMP NULL,     -- Verifikasi email
    password VARCHAR(255),                -- Password ter-hash
    remember_token VARCHAR(100) NULL,     -- Token "Ingat Saya"
    created_at TIMESTAMP,                 -- Waktu pembuatan
    updated_at TIMESTAMP                  -- Waktu update
);
```

### Tabel `mahasiswa` (Custom)
```sql
CREATE TABLE mahasiswa (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT,                       -- Foreign Key ke users
    nama_lengkap VARCHAR(255),            -- Nama mahasiswa
    tempat_lahir VARCHAR(255),
    tanggal_lahir DATE,
    jenis_kelamin ENUM('L','P'),
    agama ENUM(...),
    nama_ibu VARCHAR(255),
    kewarganegaraan ENUM('WNI','WNA'),
    nik VARCHAR(16) UNIQUE,
    passport VARCHAR(15) UNIQUE,
    alamat TEXT,
    no_hp VARCHAR(15),
    email VARCHAR(255) UNIQUE,            -- Email mahasiswa
    password VARCHAR(255),                -- Password ter-hash (backup)
    jalur_program ENUM('RPL','Non RPL'),
    jenjang ENUM('D3','D4','S1'),
    program_studi VARCHAR(255),
    jenis_pendaftaran ENUM('sarjana','magister','doktoral'),
    status_verifikasi ENUM('pending','verified','rejected'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    ...
);
```

---

## 🔄 MAPPING FIELD SAAT PENDAFTARAN

### Dari Form Pendaftaran Mahasiswa ke Tabel `users`:

| Field di Tabel `users` | Diisi Dari                | Sumber Data            |
|------------------------|---------------------------|------------------------|
| `id`                   | Auto Increment            | Database               |
| `name`                 | `nama_lengkap`            | Form mahasiswa         |
| `email`                | `email`                   | Form mahasiswa         |
| `email_verified_at`    | NULL (default)            | Akan diisi saat verify |
| `password`             | Hash(`password`)          | Form mahasiswa         |
| `remember_token`       | NULL (default)            | Akan diisi saat login  |
| `created_at`           | Auto Timestamp            | Database               |
| `updated_at`           | Auto Timestamp            | Database               |

### Dari Form Pendaftaran Mahasiswa ke Tabel `mahasiswa`:

| Field di Tabel `mahasiswa` | Diisi Dari           | Sumber Data       |
|----------------------------|----------------------|-------------------|
| `id`                       | Auto Increment       | Database          |
| `user_id`                  | `users.id`           | Dari step 1       |
| `nama_lengkap`             | `nama_lengkap`       | Form mahasiswa    |
| `tempat_lahir`             | `tempat_lahir`       | Form mahasiswa    |
| `tanggal_lahir`            | `tanggal_lahir`      | Form mahasiswa    |
| `email`                    | `email`              | Form mahasiswa    |
| `password`                 | Hash(`password`)     | Form mahasiswa    |
| `nik`                      | `nik`                | Form mahasiswa    |
| ... (semua field lain)     | ... (dari form)      | Form mahasiswa    |

---

## 💻 IMPLEMENTASI DI CONTROLLER

### Contoh di SarjanaController:

```php
// STEP 1: Buat akun di tabel USERS
$user = User::create([
    'name' => $validated['nama_lengkap'],        // ← dari mahasiswa
    'email' => $validated['email'],               // ← dari mahasiswa
    'password' => Hash::make($validated['password']), // ← dari mahasiswa
]);

// STEP 2: Buat data di tabel MAHASISWA
$validated['user_id'] = $user->id;               // ← link ke users
$validated['jenis_pendaftaran'] = 'sarjana';
$validated['status_verifikasi'] = 'pending';
$validated['password'] = Hash::make($validated['password']);

$mahasiswa = Mahasiswa::create($validated);
```

---

## 📊 CONTOH DATA SETELAH PENDAFTARAN

### Contoh Input Form:
```
Nama Lengkap: Ahmad Dahlan
Email: ahmad@example.com
Password: rahasia123
Tempat Lahir: Jakarta
Tanggal Lahir: 2000-05-15
NIK: 3201234567890123
Program Studi: Teknik Informatika
... field lainnya
```

### Hasil di Tabel `users`:

| id | name          | email              | password          | created_at          |
|----|---------------|--------------------|-------------------|---------------------|
| 1  | Ahmad Dahlan  | ahmad@example.com  | $2y$10$hashed... | 2026-01-13 10:30:00 |

### Hasil di Tabel `mahasiswa`:

| id  | user_id | nama_lengkap | email              | nik              | tempat_lahir | program_studi        | jenis_pendaftaran |
|-----|---------|--------------|--------------------|--------------------|--------------|----------------------|-------------------|
| 100 | 1       | Ahmad Dahlan | ahmad@example.com  | 3201234567890123   | Jakarta      | Teknik Informatika   | sarjana           |

### Relasi:
```
users.id (1) ←→ mahasiswa.user_id (1)
```

---

## ✅ VALIDASI MAPPING

### Field Yang SAMA di Kedua Tabel:
- ✅ `email` - Email untuk login dan identifikasi
- ✅ `password` - Password ter-hash (sama di kedua tabel)
- ✅ `created_at` - Timestamp pembuatan
- ✅ `updated_at` - Timestamp update

### Field Yang BERBEDA Tapi RELATED:
- ✅ `users.name` ← `mahasiswa.nama_lengkap`
- ✅ `users.id` ← `mahasiswa.user_id`

### Field Yang HANYA Ada di Mahasiswa:
- `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`
- `agama`, `nama_ibu`, `kewarganegaraan`
- `nik`, `passport`, `alamat`, `no_hp`
- `jalur_program`, `jenjang`, `program_studi`
- `jenis_pendaftaran`, `status_verifikasi`

---

## 🔍 CEK DATA DI DATABASE

### Query untuk Melihat Relasi:

```sql
-- Lihat data users dan mahasiswa yang terhubung
SELECT 
    u.id as user_id,
    u.name as user_name,
    u.email as user_email,
    m.id as mahasiswa_id,
    m.user_id,
    m.nama_lengkap,
    m.email as mahasiswa_email,
    m.jenis_pendaftaran,
    m.program_studi
FROM users u
LEFT JOIN mahasiswa m ON u.id = m.user_id
ORDER BY u.id DESC;
```

### Expected Result:
```
+--------+--------------+-------------------+-------------+--------+--------------+-------------------+------------------+--------------------+
|user_id | user_name    | user_email        |mahasiswa_id |user_id | nama_lengkap | mahasiswa_email   |jenis_pendaftaran | program_studi      |
+--------+--------------+-------------------+-------------+--------+--------------+-------------------+------------------+--------------------+
| 1      | Ahmad Dahlan | ahmad@example.com | 100         | 1      | Ahmad Dahlan | ahmad@example.com | sarjana          | Teknik Informatika |
+--------+--------------+-------------------+-------------+--------+--------------+-------------------+------------------+--------------------+
```

---

## 🎯 KESIMPULAN MAPPING

### Tabel `users` Tetap Standar Laravel:
```
✅ id (auto)
✅ name           ← diisi dari mahasiswa.nama_lengkap
✅ email          ← diisi dari mahasiswa.email
✅ email_verified_at (null, untuk fitur verifikasi email)
✅ password       ← diisi dari Hash::make(password form)
✅ remember_token (null, untuk "Ingat Saya")
✅ created_at (auto)
✅ updated_at (auto)
```

### Pengisian Otomatis Saat Pendaftaran:
1. User isi form pendaftaran mahasiswa
2. Data `nama_lengkap`, `email`, `password` dari form
3. Sistem otomatis:
   - Insert ke `users` → `name`, `email`, `password`
   - Insert ke `mahasiswa` → semua field + `user_id`
4. Mahasiswa bisa login dengan `email` & `password`

### Tidak Ada Perubahan Struktur Tabel `users`:
- ✅ Struktur standar Laravel dipertahankan
- ✅ Kompatibel dengan package Laravel lainnya
- ✅ Mudah untuk extend (role, permission, dll)

Sistem siap digunakan! 🚀
