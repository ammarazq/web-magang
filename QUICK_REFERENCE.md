# Quick Reference - Model Mahasiswa

## 🚀 Quick Start

### 1. Akses Data
```php
use App\Models\Mahasiswa;

// Get all
$all = Mahasiswa::all();

// Get by ID
$mahasiswa = Mahasiswa::find(1);

// Get by email
$mahasiswa = Mahasiswa::where('email', 'test@example.com')->first();
```

### 2. Filter Data
```php
// By jenis pendaftaran
$sarjana = Mahasiswa::sarjana()->get();
$magister = Mahasiswa::magister()->get();
$doktoral = Mahasiswa::doktoral()->get();

// By status
$pending = Mahasiswa::pending()->get();
$verified = Mahasiswa::verified()->get();

// Kombinasi
$sarjanaPending = Mahasiswa::sarjana()->pending()->get();
```

### 3. Create Data
```php
$mahasiswa = Mahasiswa::create([
    'nama_lengkap' => 'John Doe',
    'email' => 'john@example.com',
    'password' => Hash::make('password'),
    'jenis_pendaftaran' => 'sarjana',
    // ... other fields
]);
```

### 4. Update Data
```php
$mahasiswa = Mahasiswa::find(1);
$mahasiswa->update([
    'status_verifikasi' => 'verified',
    'verified_at' => now(),
]);
```

### 5. Delete Data
```php
// Soft delete
$mahasiswa->delete();

// Force delete
$mahasiswa->forceDelete();

// Restore
$mahasiswa->restore();
```

---

## 📋 Field Cheat Sheet

### Required for ALL
- nama_lengkap
- tempat_lahir
- tanggal_lahir
- jenis_kelamin (L/P)
- agama
- nama_ibu
- kewarganegaraan (WNI/WNA)
- no_hp
- email (unique)
- password

### Required for WNI
- nik (16 digit, unique)

### Required for WNA
- negara
- passport (unique)

### Required for Sarjana Only
- alamat
- jalur_program (RPL/Non RPL)
- jenjang (D3/D4/S1)
- program_studi

### Required for Magister/Doktoral Only
- status_kawin (Kawin/Belum Kawin)

---

## 🔍 Common Queries

```php
// Count by type
Mahasiswa::sarjana()->count();
Mahasiswa::magister()->count();
Mahasiswa::doktoral()->count();

// Pending registrations
Mahasiswa::pending()->latest()->get();

// Verified this month
Mahasiswa::verified()
    ->whereMonth('verified_at', now()->month)
    ->get();

// WNI students
Mahasiswa::where('kewarganegaraan', 'WNI')->get();

// Search by name
Mahasiswa::where('nama_lengkap', 'LIKE', '%john%')->get();

// Latest 10
Mahasiswa::latest()->take(10)->get();

// With pagination
Mahasiswa::paginate(20);
```

---

## 🧪 Testing

### Generate Test Data
```bash
php artisan db:seed --class=MahasiswaSeeder
```

### Test in Tinker
```bash
php artisan tinker

>>> Mahasiswa::count()
=> 93

>>> Mahasiswa::sarjana()->count()
=> 47

>>> $m = Mahasiswa::first()
>>> $m->formatted_nama
>>> $m->age
>>> $m->isWNI()
```

---

## 🎯 Controller Usage

### In SarjanaController
```php
$validated['jenis_pendaftaran'] = 'sarjana';
$mahasiswa = Mahasiswa::create($validated);
```

### In MagisterController
```php
$data['jenis_pendaftaran'] = 'magister';
$mahasiswa = Mahasiswa::create($data);
```

### In DoktoralController
```php
$data['jenis_pendaftaran'] = 'doktoral';
$mahasiswa = Mahasiswa::create($data);
```

---

## 📊 Statistics

```php
$stats = [
    'total' => Mahasiswa::count(),
    'sarjana' => Mahasiswa::sarjana()->count(),
    'magister' => Mahasiswa::magister()->count(),
    'doktoral' => Mahasiswa::doktoral()->count(),
    'pending' => Mahasiswa::pending()->count(),
    'verified' => Mahasiswa::verified()->count(),
    'wni' => Mahasiswa::where('kewarganegaraan', 'WNI')->count(),
    'wna' => Mahasiswa::where('kewarganegaraan', 'WNA')->count(),
];
```

---

## 🔧 Useful Commands

```bash
# Migration
php artisan migrate
php artisan migrate:rollback
php artisan migrate:fresh

# Seeding
php artisan db:seed --class=MahasiswaSeeder
php artisan migrate:fresh --seed

# Tinker
php artisan tinker

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

## 🎨 Helper Methods

```php
$mahasiswa = Mahasiswa::find(1);

// Check citizenship
$mahasiswa->isWNI();  // boolean
$mahasiswa->isWNA();  // boolean

// Get formatted data
$mahasiswa->formatted_nama;   // Ucwords
$mahasiswa->age;              // Integer
$mahasiswa->full_location;    // String
```

---

## ⚠️ Important Notes

1. **Password**: Always use `Hash::make()` or `bcrypt()`
2. **Unique Fields**: email, nik, passport
3. **Soft Delete**: Data tidak benar-benar dihapus
4. **Validation**: Selalu validasi di controller
5. **jenis_pendaftaran**: Default 'sarjana' jika tidak diset

---

## 📚 Full Documentation

See `DATABASE_SCHEMA.md` for complete documentation.
