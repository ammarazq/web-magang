# SISTEM REGISTRASI DAN LOGIN - DOKUMENTASI LENGKAP

## 📋 DAFTAR ISI
1. [Struktur Database](#struktur-database)
2. [Backend Logic](#backend-logic)
3. [Alur Sistem](#alur-sistem)
4. [Keamanan](#keamanan)
5. [Cara Penggunaan](#cara-penggunaan)

---

## 🗄️ STRUKTUR DATABASE

### Tabel: `users`

| Kolom | Tipe Data | Keterangan |
|-------|-----------|------------|
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `name` | VARCHAR(255) | Nama lengkap pengguna |
| `email` | VARCHAR(255) | Email pengguna (UNIQUE) |
| `email_verified_at` | TIMESTAMP | Waktu verifikasi email |
| `password` | VARCHAR(255) | Password ter-hash |
| `remember_token` | VARCHAR(100) | Token untuk "Remember Me" |
| `created_at` | TIMESTAMP | Waktu pendaftaran |
| `updated_at` | TIMESTAMP | Waktu update terakhir |

### Tabel: `sessions` (Untuk Session Management)

| Kolom | Tipe Data | Keterangan |
|-------|-----------|------------|
| `id` | STRING | Primary Key, Session ID |
| `user_id` | BIGINT UNSIGNED | Foreign Key ke users |
| `ip_address` | VARCHAR(45) | IP Address pengguna |
| `user_agent` | TEXT | Browser & Device info |
| `payload` | LONGTEXT | Data session |
| `last_activity` | INTEGER | Timestamp aktivitas terakhir |

### Constraints & Indexes:
- **UNIQUE INDEX** pada `users.email` untuk mencegah duplikasi email
- **FOREIGN KEY** `sessions.user_id` references `users.id`
- **INDEX** pada `sessions.user_id` dan `sessions.last_activity` untuk performa query

---

## 🔧 BACKEND LOGIC

### 1. **AuthController.php**

#### File Location: `app/Http/Controllers/AuthController.php`

### Method-Method Utama:

#### A. `register()` - Menampilkan Form Registrasi
```php
public function register()
{
    return view('auth.register');
}
```
- Menampilkan halaman form registrasi
- View: `resources/views/auth/register.blade.php`

---

#### B. `doRegister(Request $request)` - Proses Registrasi

**Validasi:**
```php
$validator = Validator::make($request->all(), [
    'name' => 'required|string|max:255',
    'email' => 'required|string|email|max:255|unique:users',
    'password' => 'required|string|min:8|confirmed',
]);
```

**Proses:**
1. **Validasi Input**
   - Nama: wajib diisi, maksimal 255 karakter
   - Email: wajib diisi, format email valid, **UNIQUE** (cek duplikasi)
   - Password: minimal 8 karakter, harus sesuai dengan konfirmasi

2. **Pembuatan User Baru**
   ```php
   $user = User::create([
       'name' => $request->name,
       'email' => $request->email,
       'password' => Hash::make($request->password), // Password di-hash
   ]);
   ```

3. **Auto-Login**
   ```php
   Auth::login($user);
   ```
   - User langsung login setelah registrasi berhasil

4. **Redirect ke Dashboard**
   - Redirect ke halaman dashboard dengan pesan sukses

**Validasi Duplikasi:**
- Rule `unique:users` pada field email memastikan tidak ada email duplikat
- Jika email sudah terdaftar, muncul error: "Email sudah terdaftar, gunakan email lain"

---

#### C. `login()` - Menampilkan Form Login
```php
public function login()
{
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
}
```
- Cek apakah user sudah login
- Jika sudah login, redirect ke dashboard
- Jika belum, tampilkan form login

---

#### D. `doLogin(Request $request)` - Proses Login

**Validasi:**
```php
$validator = Validator::make($request->all(), [
    'email' => 'required|email',
    'password' => 'required',
]);
```

**Proses:**
1. **Validasi Input**
   - Email: wajib diisi, format email valid
   - Password: wajib diisi

2. **Attempt Login**
   ```php
   $credentials = $request->only('email', 'password');
   $remember = $request->has('remember');
   
   if (Auth::attempt($credentials, $remember)) {
       $request->session()->regenerate();
       return redirect()->intended(route('dashboard'));
   }
   ```
   - Laravel otomatis melakukan pengecekan password ter-hash
   - Jika checkbox "Ingat saya" dicentang, session akan lebih lama

3. **Session Security**
   ```php
   $request->session()->regenerate();
   ```
   - Regenerate session ID untuk mencegah session fixation attack

4. **Redirect**
   - Jika berhasil: redirect ke dashboard
   - Jika gagal: kembali ke form login dengan pesan error

---

#### E. `logout(Request $request)` - Proses Logout

```php
public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    return redirect()->route('login');
}
```

**Proses:**
1. Logout user dari sistem
2. Invalidate session yang ada
3. Regenerate CSRF token untuk keamanan
4. Redirect ke halaman login

---

## 🔄 ALUR SISTEM

### A. ALUR REGISTRASI

```
┌─────────────────────────────────────────────────────────────────┐
│                      ALUR REGISTRASI                            │
└─────────────────────────────────────────────────────────────────┘

1. User mengakses /register
   │
   ├─→ GET /register
   │   └─→ AuthController::register()
   │       └─→ Tampilkan form registrasi
   │
2. User mengisi form (name, email, password, password_confirmation)
   │
3. User klik tombol "Daftar"
   │
   ├─→ POST /register
   │   └─→ AuthController::doRegister(Request $request)
   │       │
   │       ├─→ VALIDASI INPUT
   │       │   ├─ Name: required, string, max:255
   │       │   ├─ Email: required, email, max:255, unique:users
   │       │   └─ Password: required, min:8, confirmed
   │       │
   │       ├─→ CEK DUPLIKASI EMAIL
   │       │   └─ Query: SELECT * FROM users WHERE email = ?
   │       │       │
   │       │       ├─→ Jika ADA: Return error "Email sudah terdaftar"
   │       │       └─→ Jika TIDAK ADA: Lanjut
   │       │
   │       ├─→ HASH PASSWORD
   │       │   └─ $password = Hash::make($request->password)
   │       │      (Menggunakan bcrypt algorithm)
   │       │
   │       ├─→ SIMPAN KE DATABASE
   │       │   └─ INSERT INTO users (name, email, password, created_at, updated_at)
   │       │
   │       ├─→ AUTO-LOGIN
   │       │   └─ Auth::login($user)
   │       │      ├─ Buat session baru
   │       │      └─ Set session user_id
   │       │
   │       └─→ REDIRECT KE DASHBOARD
   │           └─ redirect()->route('dashboard')
   │              dengan pesan: "Registrasi berhasil!"
   │
4. User melihat halaman dashboard
```

---

### B. ALUR LOGIN

```
┌─────────────────────────────────────────────────────────────────┐
│                        ALUR LOGIN                               │
└─────────────────────────────────────────────────────────────────┘

1. User mengakses /login
   │
   ├─→ GET /login
   │   └─→ AuthController::login()
   │       │
   │       ├─→ CEK STATUS LOGIN
   │       │   └─ Auth::check()
   │       │       │
   │       │       ├─→ Jika SUDAH LOGIN: redirect()->route('dashboard')
   │       │       └─→ Jika BELUM LOGIN: Tampilkan form login
   │       │
   │       └─→ return view('auth.login')
   │
2. User mengisi form (email, password, remember_me)
   │
3. User klik tombol "Login"
   │
   ├─→ POST /login
   │   └─→ AuthController::doLogin(Request $request)
   │       │
   │       ├─→ VALIDASI INPUT
   │       │   ├─ Email: required, email
   │       │   └─ Password: required
   │       │
   │       ├─→ AMBIL CREDENTIALS
   │       │   ├─ $credentials = ['email' => ?, 'password' => ?]
   │       │   └─ $remember = checkbox status
   │       │
   │       ├─→ ATTEMPT LOGIN
   │       │   └─ Auth::attempt($credentials, $remember)
   │       │       │
   │       │       ├─→ PROSES INTERNAL:
   │       │       │   1. Query: SELECT * FROM users WHERE email = ?
   │       │       │   2. Ambil password ter-hash dari database
   │       │       │   3. Hash::check($input_password, $db_password)
   │       │       │   4. Jika MATCH: Login berhasil
   │       │       │   5. Jika TIDAK MATCH: Login gagal
   │       │       │
   │       │       ├─→ Jika BERHASIL:
   │       │       │   ├─ Buat session baru
   │       │       │   ├─ Set session: user_id, ip_address, user_agent
   │       │       │   ├─ Regenerate session ID (security)
   │       │       │   └─ Set remember_token (jika remember_me = true)
   │       │       │
   │       │       └─→ Jika GAGAL:
   │       │           └─ Return error: "Email atau password salah"
   │       │
   │       ├─→ REDIRECT
   │       │   ├─→ Jika BERHASIL: redirect()->route('dashboard')
   │       │   └─→ Jika GAGAL: redirect()->back() dengan error
   │       │
   │       └─→ SESSION REGENERATION
   │           └─ $request->session()->regenerate()
   │              (Mencegah session fixation attack)
   │
4. User melihat halaman dashboard
```

---

### C. ALUR LOGOUT

```
┌─────────────────────────────────────────────────────────────────┐
│                        ALUR LOGOUT                              │
└─────────────────────────────────────────────────────────────────┘

1. User klik tombol "Logout" di dashboard
   │
   ├─→ POST /logout
   │   └─→ AuthController::logout(Request $request)
   │       │
   │       ├─→ LOGOUT USER
   │       │   └─ Auth::logout()
   │       │      └─ Hapus data user dari session
   │       │
   │       ├─→ INVALIDATE SESSION
   │       │   └─ $request->session()->invalidate()
   │       │      └─ Hapus semua data session
   │       │
   │       ├─→ REGENERATE CSRF TOKEN
   │       │   └─ $request->session()->regenerateToken()
   │       │      └─ Buat CSRF token baru (security)
   │       │
   │       └─→ REDIRECT KE LOGIN
   │           └─ redirect()->route('login')
   │              dengan pesan: "Logout berhasil"
   │
2. User kembali ke halaman login
```

---

## 🔐 KEAMANAN

### 1. **Password Hashing**
- **Algoritma**: bcrypt (Laravel default)
- **Cost Factor**: 10 (default Laravel)
- **Implementasi**: 
  ```php
  Hash::make($password)  // Untuk hash password
  Hash::check($password, $hashed)  // Untuk verify password
  ```
- **Keuntungan**:
  - Password tidak disimpan dalam bentuk plain text
  - Setiap password memiliki salt unik
  - Resistant terhadap rainbow table attacks

### 2. **Validasi Duplikasi Email**
- **Rule**: `email|unique:users`
- **Query**: 
  ```sql
  SELECT COUNT(*) FROM users WHERE email = ?
  ```
- **Response**: Error message jika email sudah terdaftar

### 3. **CSRF Protection**
- Setiap form menggunakan `@csrf` token
- Laravel otomatis memverifikasi token pada setiap POST request
- Token di-regenerate setelah logout

### 4. **Session Security**
- Session ID di-regenerate setelah login (mencegah session fixation)
- Session di-invalidate setelah logout
- Session timeout otomatis (default: 120 menit)

### 5. **Remember Me Token**
- Token unik untuk "Ingat Saya" feature
- Disimpan dalam cookie ter-enkripsi
- Expired setelah periode tertentu (default: 2 minggu)

### 6. **Input Validation**
- Semua input divalidasi sebelum diproses
- XSS protection melalui Blade templating
- SQL Injection protection melalui Eloquent ORM

---

## 🚀 CARA PENGGUNAAN

### 1. **Setup Database**

Jalankan migration:
```bash
php artisan migrate
```

Migration akan membuat tabel:
- `users`
- `sessions`
- `password_reset_tokens`

### 2. **Akses Sistem**

#### Registrasi:
1. Buka browser: `http://localhost:8000/register`
2. Isi form:
   - Nama Lengkap
   - Email (akan dicek duplikasi)
   - Password (minimal 8 karakter)
   - Konfirmasi Password
3. Klik "Daftar"
4. Otomatis login dan redirect ke dashboard

#### Login:
1. Buka browser: `http://localhost:8000/login`
2. Isi form:
   - Email (yang sudah terdaftar)
   - Password
   - (Opsional) Centang "Ingat saya"
3. Klik "Login"
4. Redirect ke dashboard

#### Logout:
1. Di halaman dashboard, klik tombol "Logout"
2. Session dihapus
3. Redirect ke halaman login

---

## 📊 FLOW DIAGRAM KESELURUHAN

```
┌─────────────────────────────────────────────────────────────────┐
│                   SISTEM REGISTRASI & LOGIN                     │
└─────────────────────────────────────────────────────────────────┘

                    ┌──────────────┐
                    │   Browser    │
                    └──────┬───────┘
                           │
              ┌────────────┴────────────┐
              │                         │
        ┌─────▼─────┐             ┌────▼────┐
        │ /register │             │ /login  │
        └─────┬─────┘             └────┬────┘
              │                        │
              │                        │
    ┌─────────▼──────────┐    ┌────────▼─────────┐
    │ Form Registrasi    │    │   Form Login     │
    │ - Name             │    │   - Email        │
    │ - Email            │    │   - Password     │
    │ - Password         │    │   - Remember Me  │
    │ - Password Confirm │    └────────┬─────────┘
    └─────────┬──────────┘             │
              │                        │
       POST /register           POST /login
              │                        │
    ┌─────────▼──────────┐    ┌────────▼─────────┐
    │ AuthController     │    │ AuthController   │
    │ ::doRegister()     │    │ ::doLogin()      │
    └─────────┬──────────┘    └────────┬─────────┘
              │                        │
    ┌─────────▼──────────┐    ┌────────▼─────────┐
    │ Validasi Input     │    │ Validasi Input   │
    │ - Required fields  │    │ - Email format   │
    │ - Email format     │    │ - Required pass  │
    │ - Password min 8   │    └────────┬─────────┘
    │ - Password confirm │             │
    └─────────┬──────────┘             │
              │                        │
    ┌─────────▼──────────┐    ┌────────▼─────────┐
    │ Cek Duplikasi      │    │ Auth::attempt()  │
    │ Email (unique)     │    │ - Cari user      │
    └─────────┬──────────┘    │ - Verify hash    │
              │                └────────┬─────────┘
         ┌────▼────┐                   │
         │ Valid?  │              ┌────▼────┐
         └────┬────┘              │ Valid?  │
              │                   └────┬────┘
         Yes  │  No                    │
    ┌─────────▼──────────┐   Yes      │      No
    │ Hash Password      │    ┌────────▼─────────┐
    │ Hash::make()       │    │ Create Session   │
    └─────────┬──────────┘    │ - Set user_id    │
              │                │ - Regenerate ID  │
    ┌─────────▼──────────┐    └────────┬─────────┘
    │ Simpan ke Database │             │
    │ INSERT INTO users  │             │
    └─────────┬──────────┘             │
              │                        │
    ┌─────────▼──────────┐    ┌────────▼─────────┐
    │ Auto Login         │    │ Redirect to      │
    │ Auth::login($user) │    │ Dashboard        │
    └─────────┬──────────┘    └────────┬─────────┘
              │                        │
              └────────────┬───────────┘
                           │
                    ┌──────▼───────┐
                    │   Dashboard  │
                    │              │
                    │ - User Info  │
                    │ - Logout Btn │
                    └──────┬───────┘
                           │
                    POST /logout
                           │
                    ┌──────▼───────┐
                    │ Auth::logout()│
                    │ Invalidate    │
                    │ Session       │
                    └──────┬───────┘
                           │
                    ┌──────▼───────┐
                    │ Redirect to  │
                    │ Login Page   │
                    └──────────────┘
```

---

## 📝 FILE STRUKTUR

```
project/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── AuthController.php       # Controller utama
│   └── Models/
│       └── User.php                     # Model User
│
├── database/
│   └── migrations/
│       └── 0001_01_01_000000_create_users_table.php  # Migration
│
├── resources/
│   └── views/
│       └── auth/
│           ├── register.blade.php       # Form registrasi
│           ├── login.blade.php          # Form login
│           └── dashboard.blade.php      # Dashboard user
│
└── routes/
    └── web.php                          # Routing
```

---

## ✅ FITUR YANG SUDAH DIIMPLEMENTASI

1. ✅ **Registrasi Pengguna**
   - Form input (nama, email, password)
   - Validasi input lengkap
   - Duplikasi email detection
   - Password hashing otomatis
   - Auto-login setelah registrasi

2. ✅ **Login Pengguna**
   - Form login (email, password)
   - Remember me functionality
   - Session management
   - Password verification
   - Redirect ke dashboard

3. ✅ **Logout**
   - Hapus session
   - Invalidate token
   - Redirect ke login

4. ✅ **Dashboard**
   - Menampilkan info user
   - Protected route (harus login)
   - Logout button

5. ✅ **Keamanan**
   - Password hashing (bcrypt)
   - CSRF protection
   - Session regeneration
   - Input validation
   - XSS protection

6. ✅ **User Experience**
   - Error messages yang jelas
   - Success notifications
   - Form validation feedback
   - Responsive design

---

## 🎯 KESIMPULAN

Sistem registrasi dan login ini sudah lengkap dengan:
- ✅ Username & password hasil registrasi langsung bisa login
- ✅ Password di-hash menggunakan bcrypt
- ✅ Validasi duplikasi akun (unique email)
- ✅ Session management yang aman
- ✅ UI/UX yang user-friendly

Sistem siap digunakan! 🚀
