# Database Schema - Model Mahasiswa

## 📋 Overview

Model `Mahasiswa` digunakan untuk menyimpan data pendaftaran mahasiswa dari 3 jenis program:
- **Sarjana** (D3/D4/S1)
- **Magister** (S2)
- **Doktoral** (S3)

---

## 🗄️ Database Structure

### Tabel: `mahasiswa`

| Column | Type | Nullable | Unique | Default | Description |
|--------|------|----------|--------|---------|-------------|
| `id` | BIGINT UNSIGNED | NO | YES | AUTO_INCREMENT | Primary Key |
| **DATA PRIBADI** |
| `nama_lengkap` | VARCHAR(255) | NO | NO | - | Nama lengkap mahasiswa |
| `tempat_lahir` | VARCHAR(255) | NO | NO | - | Tempat lahir |
| `tanggal_lahir` | DATE | NO | NO | - | Tanggal lahir |
| `jenis_kelamin` | ENUM('L','P') | NO | NO | - | L = Laki-laki, P = Perempuan |
| `agama` | ENUM | NO | NO | - | Islam, Protestan, Katolik, Hindu, Budha, Konghucu |
| `nama_ibu` | VARCHAR(255) | NO | NO | - | Nama ibu kandung |
| `status_kawin` | ENUM | YES | NO | NULL | Kawin, Belum Kawin (untuk Magister/Doktoral) |
| **DATA KEWARGANEGARAAN** |
| `kewarganegaraan` | ENUM('WNI','WNA') | NO | NO | - | Kewarganegaraan |
| `nik` | VARCHAR(16) | YES | YES | NULL | NIK 16 digit (untuk WNI) |
| `negara` | VARCHAR(255) | YES | NO | NULL | Negara asal (untuk WNA) |
| `passport` | VARCHAR(15) | YES | YES | NULL | Nomor passport (untuk WNA) |
| **DATA KONTAK** |
| `alamat` | TEXT | YES | NO | NULL | Alamat lengkap (untuk Sarjana) |
| `no_hp` | VARCHAR(15) | NO | NO | - | Nomor HP/WA (10-15 digit) |
| `email` | VARCHAR(255) | NO | YES | - | Email unik |
| `password` | VARCHAR(255) | NO | NO | - | Password (hashed) |
| **DATA AKADEMIK** |
| `jalur_program` | ENUM('RPL','Non RPL') | YES | NO | NULL | Jalur program (khusus Sarjana) |
| `jenjang` | ENUM('D3','D4','S1') | YES | NO | NULL | Jenjang (khusus Sarjana) |
| `program_studi` | VARCHAR(255) | YES | NO | NULL | Program studi (khusus Sarjana) |
| **JENIS & STATUS** |
| `jenis_pendaftaran` | ENUM | NO | NO | 'sarjana' | sarjana, magister, doktoral |
| `status_verifikasi` | ENUM | NO | NO | 'pending' | pending, verified, rejected |
| `catatan_verifikasi` | TEXT | YES | NO | NULL | Catatan dari admin |
| **METADATA** |
| `email_verified_at` | TIMESTAMP | YES | NO | NULL | Waktu verifikasi email |
| `verified_by` | BIGINT UNSIGNED | YES | NO | NULL | ID admin yang verifikasi |
| `verified_at` | TIMESTAMP | YES | NO | NULL | Waktu verifikasi admin |
| `created_at` | TIMESTAMP | YES | NO | NULL | Waktu dibuat |
| `updated_at` | TIMESTAMP | YES | NO | NULL | Waktu diupdate |
| `deleted_at` | TIMESTAMP | YES | NO | NULL | Soft delete timestamp |

### Indexes

```sql
INDEX: mahasiswa_email_index (email)
INDEX: mahasiswa_nik_index (nik)
INDEX: mahasiswa_passport_index (passport)
INDEX: mahasiswa_jenis_pendaftaran_index (jenis_pendaftaran)
INDEX: mahasiswa_status_verifikasi_index (status_verifikasi)
INDEX: mahasiswa_jenis_pendaftaran_status_verifikasi_index (jenis_pendaftaran, status_verifikasi)
INDEX: mahasiswa_created_at_index (created_at)
```

---

## 📦 Model Features

### Mass Assignment

```php
protected $fillable = [
    'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
    'agama', 'nama_ibu', 'status_kawin', 'kewarganegaraan', 'nik',
    'negara', 'passport', 'alamat', 'no_hp', 'email', 'password',
    'jalur_program', 'jenjang', 'program_studi', 'jenis_pendaftaran',
    'status_verifikasi', 'catatan_verifikasi', 'email_verified_at',
    'verified_by', 'verified_at'
];
```

### Hidden Attributes

```php
protected $hidden = ['password'];
```

### Casts

```php
protected $casts = [
    'tanggal_lahir' => 'date',
    'email_verified_at' => 'datetime',
    'verified_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
];
```

---

## 🔍 Query Scopes

### Filter by Jenis Pendaftaran

```php
// Get all Sarjana mahasiswa
$sarjana = Mahasiswa::sarjana()->get();

// Get all Magister mahasiswa
$magister = Mahasiswa::magister()->get();

// Get all Doktoral mahasiswa
$doktoral = Mahasiswa::doktoral()->get();
```

### Filter by Status Verifikasi

```php
// Get pending mahasiswa
$pending = Mahasiswa::pending()->get();

// Get verified mahasiswa
$verified = Mahasiswa::verified()->get();

// Get specific status
$rejected = Mahasiswa::statusVerifikasi('rejected')->get();
```

### Kombinasi Scopes

```php
// Sarjana yang pending
$sarjanaPending = Mahasiswa::sarjana()->pending()->get();

// Magister yang verified
$magisterVerified = Mahasiswa::magister()->verified()->get();

// Doktoral WNI yang pending
$doktoralWNI = Mahasiswa::doktoral()
    ->pending()
    ->where('kewarganegaraan', 'WNI')
    ->get();
```

---

## 🛠️ Helper Methods

### Check Kewarganegaraan

```php
$mahasiswa = Mahasiswa::find(1);

// Check if WNI
if ($mahasiswa->isWNI()) {
    echo "NIK: " . $mahasiswa->nik;
}

// Check if WNA
if ($mahasiswa->isWNA()) {
    echo "Passport: " . $mahasiswa->passport;
}
```

### Computed Attributes

```php
$mahasiswa = Mahasiswa::find(1);

// Get formatted nama
echo $mahasiswa->formatted_nama; // "John Doe"

// Get age from tanggal_lahir
echo $mahasiswa->age; // 25

// Get full location
echo $mahasiswa->full_location; // "Jakarta, Jl. Sudirman No. 123"
```

---

## 📝 Usage Examples

### Create Sarjana

```php
$sarjana = Mahasiswa::create([
    'nama_lengkap' => 'John Doe',
    'tempat_lahir' => 'Jakarta',
    'tanggal_lahir' => '2000-01-01',
    'jenis_kelamin' => 'L',
    'agama' => 'Islam',
    'nama_ibu' => 'Jane Doe',
    'kewarganegaraan' => 'WNI',
    'nik' => '1234567890123456',
    'alamat' => 'Jl. Sudirman No. 123',
    'no_hp' => '081234567890',
    'email' => 'john@example.com',
    'password' => Hash::make('password'),
    'jalur_program' => 'RPL',
    'jenjang' => 'S1',
    'program_studi' => 'Teknik Informatika',
    'jenis_pendaftaran' => 'sarjana',
]);
```

### Create Magister

```php
$magister = Mahasiswa::create([
    'nama_lengkap' => 'Jane Smith',
    'tempat_lahir' => 'Bandung',
    'tanggal_lahir' => '1995-05-15',
    'jenis_kelamin' => 'P',
    'agama' => 'Katolik',
    'nama_ibu' => 'Mary Smith',
    'status_kawin' => 'Belum Kawin',
    'kewarganegaraan' => 'WNI',
    'nik' => '9876543210987654',
    'no_hp' => '089876543210',
    'email' => 'jane@example.com',
    'password' => Hash::make('password'),
    'jenis_pendaftaran' => 'magister',
]);
```

### Create WNA Student

```php
$wna = Mahasiswa::create([
    'nama_lengkap' => 'John Smith',
    'tempat_lahir' => 'Singapore',
    'tanggal_lahir' => '1998-12-25',
    'jenis_kelamin' => 'L',
    'agama' => 'Hindu',
    'nama_ibu' => 'Mary Smith',
    'kewarganegaraan' => 'WNA',
    'negara' => 'Singapore',
    'passport' => 'SG12345678',
    'alamat' => '123 Marina Bay',
    'no_hp' => '087654321098',
    'email' => 'johnsmith@example.com',
    'password' => Hash::make('password'),
    'jalur_program' => 'Non RPL',
    'jenjang' => 'S1',
    'program_studi' => 'Manajemen',
    'jenis_pendaftaran' => 'sarjana',
]);
```

### Update Status Verifikasi

```php
$mahasiswa = Mahasiswa::find(1);

// Approve
$mahasiswa->update([
    'status_verifikasi' => 'verified',
    'verified_by' => auth()->id(),
    'verified_at' => now(),
    'email_verified_at' => now(),
]);

// Reject
$mahasiswa->update([
    'status_verifikasi' => 'rejected',
    'catatan_verifikasi' => 'Data tidak lengkap',
]);
```

### Query Examples

```php
// Get all pending Sarjana from 2026
$newSarjana = Mahasiswa::sarjana()
    ->pending()
    ->whereYear('created_at', 2026)
    ->orderBy('created_at', 'desc')
    ->get();

// Get verified Magister WNI
$verifiedMagister = Mahasiswa::magister()
    ->verified()
    ->where('kewarganegaraan', 'WNI')
    ->get();

// Count by jenis pendaftaran
$sarjanaCount = Mahasiswa::sarjana()->count();
$magisterCount = Mahasiswa::magister()->count();
$doktoralCount = Mahasiswa::doktoral()->count();

// Get statistics
$stats = [
    'total' => Mahasiswa::count(),
    'pending' => Mahasiswa::pending()->count(),
    'verified' => Mahasiswa::verified()->count(),
    'rejected' => Mahasiswa::statusVerifikasi('rejected')->count(),
    'wni' => Mahasiswa::where('kewarganegaraan', 'WNI')->count(),
    'wna' => Mahasiswa::where('kewarganegaraan', 'WNA')->count(),
];
```

---

## 🧪 Factory Usage

### Generate Test Data

```php
// Generate 10 Sarjana
Mahasiswa::factory()->count(10)->sarjana()->create();

// Generate 5 verified Magister
Mahasiswa::factory()->count(5)->magister()->verified()->create();

// Generate 3 pending Doktoral WNI
Mahasiswa::factory()->count(3)->doktoral()->pending()->wni()->create();

// Generate 2 WNA Sarjana
Mahasiswa::factory()->count(2)->sarjana()->wna()->create();
```

---

## 🔧 Migration Commands

### Run Migration

```bash
php artisan migrate
```

### Rollback

```bash
php artisan migrate:rollback
```

### Fresh Migration

```bash
php artisan migrate:fresh
```

### Seed Data

```bash
php artisan db:seed --class=MahasiswaSeeder
```

### Fresh Migration + Seed

```bash
php artisan migrate:fresh --seed
```

---

## 📊 Validation Rules

### Sarjana

- nama_lengkap: required, string, max:255
- tempat_lahir: required, string, max:255
- tanggal_lahir: required, date, before:-15 years
- jenis_kelamin: required, in:L,P
- agama: required, in:Islam,Protestan,Katolik,Hindu,Budha,Konghucu
- nama_ibu: required, string, max:255
- kewarganegaraan: required, in:WNI,WNA
- nik: required_if:kewarganegaraan,WNI, digits:16, unique
- negara: required_if:kewarganegaraan,WNA, string, max:100
- passport: required_if:kewarganegaraan,WNA, max:15, unique
- alamat: required, string
- jalur_program: required, in:RPL,Non RPL
- jenjang: required, in:D3,D4,S1
- program_studi: required, string
- no_hp: required, numeric, digits_between:10,15
- email: required, email, unique
- password: required, min:8, confirmed

### Magister/Doktoral

- nama_lengkap: required, string, max:255
- tempat_lahir: required, string, max:255
- tanggal_lahir: required, date, before:-15 years
- jenis_kelamin: required, in:L,P
- agama: required, in:Islam,Protestan,Katolik,Hindu,Budha,Konghucu
- nama_ibu: required, string, max:255
- status_kawin: required, in:Kawin,Belum Kawin
- kewarganegaraan: required, in:WNI,WNA
- nik: required, digits:16, unique
- no_hp: required, numeric, digits_between:10,15
- email: required, email, unique
- password: required, min:6, confirmed

---

## 🔐 Security Notes

1. **Password Hashing**: Selalu gunakan `Hash::make()` atau `bcrypt()`
2. **Mass Assignment**: Field `password` ada di `$fillable` tapi hidden di JSON
3. **Soft Deletes**: Data tidak benar-benar dihapus, hanya ditandai
4. **Unique Constraints**: Email, NIK, dan Passport harus unik
5. **Validation**: Selalu validasi di controller sebelum save

---

## 📈 Performance Tips

1. **Use Indexes**: Query by `jenis_pendaftaran`, `status_verifikasi`, `email`, `nik` sudah diindex
2. **Eager Loading**: Jika ada relasi, gunakan `with()`
3. **Chunk**: Untuk data besar, gunakan `chunk()` atau `cursor()`
4. **Select Specific Columns**: Jangan select semua jika tidak perlu

```php
// Good
Mahasiswa::select('id', 'nama_lengkap', 'email')->get();

// Bad (for large dataset)
Mahasiswa::all();
```

---

## 🎯 Next Steps

1. ✅ Model & Migration sudah dibuat
2. ✅ Factory & Seeder sudah dibuat
3. ✅ Controller sudah diupdate
4. 🔄 Buat admin panel untuk verifikasi
5. 🔄 Tambahkan email notification
6. 🔄 Implementasi authentication
7. 🔄 Export data ke Excel/PDF

---

**Created:** January 3, 2026  
**Laravel Version:** 10.x+  
**Database:** MySQL 5.7+
