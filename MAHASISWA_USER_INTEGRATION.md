# INTEGRASI SISTEM PENDAFTARAN MAHASISWA DENGAN USERS

## 📋 RINGKASAN PERUBAHAN

Sistem pendaftaran mahasiswa (Sarjana, Magister, Doktoral) sekarang **otomatis membuat akun di tabel `users`** sehingga mahasiswa yang mendaftar dapat langsung login menggunakan email dan password yang didaftarkan.

---

## 🔄 PERUBAHAN SISTEM

### 1. **Database Migration Baru**

#### File: `database/migrations/2026_01_13_000001_add_user_id_to_mahasiswa_table.php`

Menambahkan kolom `user_id` di tabel `mahasiswa` untuk relasi dengan tabel `users`:

```php
Schema::table('mahasiswa', function (Blueprint $table) {
    $table->unsignedBigInteger('user_id')->nullable()->after('id');
    $table->foreign('user_id')
          ->references('id')
          ->on('users')
          ->onDelete('cascade');
    $table->index('user_id');
});
```

**Struktur Relasi:**
```
users (id, name, email, password)
  │
  │ 1:1 relationship
  │
mahasiswa (id, user_id, nama_lengkap, email, password, ...)
```

---

### 2. **Model Updates**

#### A. Model Mahasiswa (`app/Models/Mahasiswa.php`)

**Tambahan:**
- Field `user_id` di `$fillable`
- Method relasi `user()`

```php
protected $fillable = [
    'user_id',          // ← BARU
    'nama_lengkap',
    'tempat_lahir',
    // ... field lainnya
];

// Relasi dengan User
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
```

#### B. Model User (`app/Models/User.php`)

**Tambahan:**
- Method relasi `mahasiswa()`

```php
public function mahasiswa()
{
    return $this->hasOne(Mahasiswa::class, 'user_id');
}
```

---

### 3. **Controller Updates**

Semua controller pendaftaran diupdate:
- ✅ `SarjanaController`
- ✅ `MagisterController`
- ✅ `DoktoralController`

#### Perubahan Proses Pendaftaran:

**SEBELUM:**
```
1. Validasi input
2. Hash password
3. Simpan ke tabel mahasiswa
4. Redirect dengan pesan sukses
```

**SESUDAH:**
```
1. Validasi input
2. BEGIN TRANSACTION
3. Buat akun di tabel users (name, email, password)
4. Simpan ke tabel mahasiswa dengan user_id
5. COMMIT TRANSACTION
6. Redirect dengan pesan sukses + info bisa login
```

#### Contoh Implementasi (SarjanaController):

```php
try {
    DB::beginTransaction();
    
    // 1. BUAT AKUN USER
    $user = User::create([
        'name' => $validated['nama_lengkap'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);
    
    // 2. BUAT DATA MAHASISWA
    $validated['user_id'] = $user->id;
    $validated['jenis_pendaftaran'] = 'sarjana';
    $validated['status_verifikasi'] = 'pending';
    $validated['password'] = Hash::make($validated['password']);
    
    $mahasiswa = Mahasiswa::create($validated);
    
    DB::commit();
    
    return redirect()->route('sarjana')
        ->with('success', 'Pendaftaran berhasil! ... Anda sudah bisa login.');
        
} catch (\Exception $e) {
    DB::rollBack();
    return back()->withErrors(['error' => $e->getMessage()]);
}
```

---

## 📊 STRUKTUR DATABASE FINAL

### Tabel: `users`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT | Primary Key |
| name | VARCHAR(255) | Nama dari mahasiswa.nama_lengkap |
| email | VARCHAR(255) | Email (UNIQUE) |
| password | VARCHAR(255) | Password ter-hash |
| remember_token | VARCHAR(100) | Token "Ingat Saya" |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

### Tabel: `mahasiswa`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT | Primary Key |
| **user_id** | BIGINT | **Foreign Key → users.id** |
| nama_lengkap | VARCHAR(255) | - |
| tempat_lahir | VARCHAR(255) | - |
| tanggal_lahir | DATE | - |
| jenis_kelamin | ENUM('L','P') | - |
| agama | ENUM | - |
| nama_ibu | VARCHAR(255) | - |
| kewarganegaraan | ENUM('WNI','WNA') | - |
| nik | VARCHAR(16) | Untuk WNI (UNIQUE) |
| passport | VARCHAR(15) | Untuk WNA (UNIQUE) |
| alamat | TEXT | - |
| no_hp | VARCHAR(15) | - |
| email | VARCHAR(255) | UNIQUE |
| password | VARCHAR(255) | Password ter-hash (backup) |
| jalur_program | ENUM | RPL / Non RPL |
| jenjang | ENUM | D3, D4, S1 |
| program_studi | VARCHAR(255) | - |
| jenis_pendaftaran | ENUM | sarjana/magister/doktoral |
| status_verifikasi | ENUM | pending/verified/rejected |
| created_at | TIMESTAMP | - |
| updated_at | TIMESTAMP | - |

### Relasi Database:

```
┌──────────────────┐          ┌──────────────────────┐
│     users        │          │     mahasiswa        │
├──────────────────┤          ├──────────────────────┤
│ id (PK)          │◄─────────│ user_id (FK)         │
│ name             │   1:1    │ id (PK)              │
│ email (UNIQUE)   │          │ nama_lengkap         │
│ password         │          │ email (UNIQUE)       │
│ ...              │          │ password             │
└──────────────────┘          │ ...                  │
                              └──────────────────────┘
```

---

## 🔄 ALUR PROSES LENGKAP

### PENDAFTARAN MAHASISWA

```
┌─────────────────────────────────────────────────────────────────┐
│              ALUR PENDAFTARAN MAHASISWA BARU                    │
└─────────────────────────────────────────────────────────────────┘

1. Mahasiswa mengisi form pendaftaran
   ├─ Nama Lengkap
   ├─ Email
   ├─ Password
   ├─ Data pribadi lainnya
   └─ CAPTCHA

2. Submit form → POST /sarjana/submit (atau /magister, /doktoral)
   
3. VALIDASI INPUT
   ├─ Validasi format email
   ├─ Cek email unique di tabel mahasiswa
   ├─ Validasi password (min 8 karakter)
   ├─ Validasi CAPTCHA
   └─ Validasi field wajib lainnya

4. BEGIN DATABASE TRANSACTION
   
5. BUAT AKUN DI TABEL USERS
   ├─ INSERT INTO users
   │   name = nama_lengkap
   │   email = email
   │   password = Hash::make(password)
   └─ Return: user_id

6. BUAT DATA MAHASISWA
   ├─ INSERT INTO mahasiswa
   │   user_id = user_id (dari step 5)
   │   nama_lengkap, email, password (hashed)
   │   jenis_pendaftaran = 'sarjana'/'magister'/'doktoral'
   │   status_verifikasi = 'pending'
   │   ... semua field lainnya
   └─ Return: mahasiswa_id

7. COMMIT TRANSACTION
   └─ Jika error → ROLLBACK (kedua tabel tidak ada data)

8. REDIRECT dengan pesan sukses
   └─ "Pendaftaran berhasil! No. Registrasi: {id}"
       "Anda sudah bisa login dengan email dan password"

9. Mahasiswa bisa LANGSUNG LOGIN
   ├─ Buka /login
   ├─ Masukkan email & password
   └─ Berhasil masuk ke dashboard
```

---

### LOGIN MAHASISWA

```
┌─────────────────────────────────────────────────────────────────┐
│                    ALUR LOGIN MAHASISWA                         │
└─────────────────────────────────────────────────────────────────┘

1. Mahasiswa membuka /login

2. Masukkan credentials:
   ├─ Email: email@example.com
   └─ Password: password123

3. Submit → POST /login
   
4. AuthController::doLogin()
   ├─ Validasi format input
   └─ Auth::attempt(['email' => ?, 'password' => ?])

5. PROSES AUTHENTICATION
   ├─ Query: SELECT * FROM users WHERE email = ?
   ├─ Ambil password ter-hash dari database
   ├─ Hash::check($input_password, $db_password)
   └─ Jika MATCH → Login berhasil

6. CREATE SESSION
   ├─ Set session user_id
   ├─ Set session data lainnya
   └─ Regenerate session ID (security)

7. REDIRECT ke /dashboard
   └─ Mahasiswa bisa melihat info akunnya

8. AKSES DATA MAHASISWA (jika diperlukan)
   └─ Auth::user()->mahasiswa
       ├─ Ambil data lengkap dari tabel mahasiswa
       ├─ No. Registrasi
       ├─ Status Verifikasi
       └─ Program Studi, dll
```

---

## 🔐 KEAMANAN

### 1. **Database Transaction**
- Menggunakan `DB::beginTransaction()` dan `DB::commit()`
- Jika terjadi error saat membuat user ATAU mahasiswa → ROLLBACK
- Memastikan data konsisten (tidak ada orphan records)

### 2. **Password Hashing**
- Password di-hash 2 kali:
  - Di tabel `users` (untuk login)
  - Di tabel `mahasiswa` (untuk backup/referensi)
- Menggunakan bcrypt algorithm (Laravel default)

### 3. **Validasi Duplikasi**
- Email di-cek di tabel `mahasiswa` (rule: `unique:mahasiswa,email`)
- Laravel otomatis cek di tabel `users` juga karena ada constraint

### 4. **Foreign Key Constraint**
- `onDelete('cascade')`: Jika user dihapus, data mahasiswa ikut terhapus
- Menjaga integritas referensial database

---

## 📝 CARA PENGGUNAAN

### 1. **Jalankan Migration Baru**

```bash
php artisan migrate
```

Output:
```
Migration: 2026_01_13_000001_add_user_id_to_mahasiswa_table
Migrating: 2026_01_13_000001_add_user_id_to_mahasiswa_table
Migrated:  2026_01_13_000001_add_user_id_to_mahasiswa_table (0.05 seconds)
```

### 2. **Test Pendaftaran**

1. Buka form pendaftaran:
   - Sarjana: `http://localhost:8000/sarjana`
   - Magister: `http://localhost:8000/magister`
   - Doktoral: `http://localhost:8000/doktoral`

2. Isi form lengkap dengan data valid

3. Submit form

4. Cek database:
```sql
-- Cek tabel users
SELECT * FROM users ORDER BY id DESC LIMIT 1;

-- Cek tabel mahasiswa
SELECT * FROM mahasiswa ORDER BY id DESC LIMIT 1;

-- Cek relasi
SELECT u.id, u.name, u.email, m.user_id, m.nama_lengkap, m.jenis_pendaftaran
FROM users u
JOIN mahasiswa m ON u.id = m.user_id
ORDER BY u.id DESC;
```

### 3. **Test Login**

1. Buka: `http://localhost:8000/login`

2. Gunakan email dan password yang baru didaftarkan

3. Klik "Login"

4. Berhasil masuk ke dashboard

### 4. **Akses Data Mahasiswa di Controller**

```php
// Di dashboard atau controller lain
$user = Auth::user();
$mahasiswa = $user->mahasiswa;

return view('dashboard', [
    'user' => $user,
    'mahasiswa' => $mahasiswa
]);
```

Di Blade:
```blade
<h1>Selamat datang, {{ Auth::user()->name }}</h1>

@if(Auth::user()->mahasiswa)
    <p>No. Registrasi: {{ Auth::user()->mahasiswa->id }}</p>
    <p>Program: {{ Auth::user()->mahasiswa->jenis_pendaftaran }}</p>
    <p>Status: {{ Auth::user()->mahasiswa->status_verifikasi }}</p>
@endif
```

---

## 🎯 MANFAAT INTEGRASI

1. ✅ **Single Sign-On**: Satu akun untuk semua layanan
2. ✅ **Data Konsisten**: User dan mahasiswa selalu sinkron
3. ✅ **Easy Authentication**: Gunakan sistem auth Laravel
4. ✅ **Better UX**: Mahasiswa langsung bisa login setelah daftar
5. ✅ **Secure**: Database transaction mencegah data corruption
6. ✅ **Scalable**: Mudah menambahkan fitur berbasis role/permission

---

## 🔄 UPDATE PADA FILE

### File yang Dimodifikasi:
1. ✅ `app/Models/Mahasiswa.php` - Tambah relasi user()
2. ✅ `app/Models/User.php` - Tambah relasi mahasiswa()
3. ✅ `app/Http/Controllers/SarjanaController.php` - Auto create user
4. ✅ `app/Http/Controllers/MagisterController.php` - Auto create user
5. ✅ `app/Http/Controllers/DoktoralController.php` - Auto create user

### File yang Dibuat:
1. ✅ `database/migrations/2026_01_13_000001_add_user_id_to_mahasiswa_table.php`

---

## ✨ KESIMPULAN

Sistem pendaftaran mahasiswa sekarang **terintegrasi penuh** dengan sistem authentication. Setiap mahasiswa yang mendaftar otomatis mendapat akun login yang bisa digunakan untuk mengakses sistem dengan email dan password yang sama.

**Flow lengkap:**
```
Daftar → Data masuk ke mahasiswa + users → Bisa login → Akses dashboard
```

Sistem siap digunakan! 🚀
