# 🎓 Sistem Pendaftaran Mahasiswa - SALUT Insan Cendekia

> Laravel Application dengan Custom CAPTCHA dan Database Model Complete

---

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Database Schema](#database-schema)
- [Usage](#usage)
- [Documentation](#documentation)
- [Testing](#testing)
- [API Reference](#api-reference)

---

## 🎯 Overview

Aplikasi pendaftaran mahasiswa online untuk 3 jenis program:
1. **Sarjana** (D3/D4/S1)
2. **Magister** (S2)
3. **Doktoral** (S3)

Dengan fitur keamanan **Custom Arithmetic CAPTCHA** dan sistem database yang terintegrasi.

---

## ✨ Features

### 🔐 Security
- ✅ Custom Arithmetic CAPTCHA (tanpa library eksternal)
- ✅ AJAX CAPTCHA refresh tanpa reload
- ✅ Server-side validation
- ✅ Password hashing dengan bcrypt
- ✅ CSRF protection
- ✅ Unique constraints (email, NIK, passport)

### 📝 Registration Forms
- ✅ Form Sarjana dengan field khusus (jalur, jenjang, prodi)
- ✅ Form Magister dengan status kawin
- ✅ Form Doktoral dengan status kawin
- ✅ Support WNI (NIK) dan WNA (Passport)
- ✅ Real-time validation
- ✅ Error handling yang informatif

### 💾 Database
- ✅ Model Mahasiswa lengkap
- ✅ Migration dengan indexes
- ✅ Factory untuk test data
- ✅ Seeder dengan sample data
- ✅ Soft deletes
- ✅ Query scopes
- ✅ Helper methods

### 🎨 UI/UX
- ✅ Responsive design (Bootstrap)
- ✅ Modal confirmations
- ✅ Real-time feedback
- ✅ Loading states
- ✅ Success/error messages

---

## 🛠️ Tech Stack

- **Framework:** Laravel 10.x
- **PHP:** 8.0+
- **Database:** MySQL 5.7+
- **Frontend:** Bootstrap 5, jQuery
- **Icons:** Font Awesome
- **Session:** File/Database driver

---

## 📦 Installation

### 1. Clone Repository
```bash
git clone <repository-url>
cd trial_two
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration
Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

SESSION_DRIVER=file  # or database
```

### 5. Run Migrations
```bash
php artisan migrate
```

### 6. Seed Database (Optional)
```bash
php artisan db:seed --class=MahasiswaSeeder
```

### 7. Start Server
```bash
php artisan serve
```

Visit: `http://localhost:8000`

---

## 🗄️ Database Schema

### Tabel: `mahasiswa`

**Total Fields:** 31 columns

| Category | Fields |
|----------|--------|
| **Pribadi** | nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, nama_ibu, status_kawin |
| **Kewarganegaraan** | kewarganegaraan, nik, negara, passport |
| **Kontak** | alamat, no_hp, email, password |
| **Akademik** | jalur_program, jenjang, program_studi |
| **Status** | jenis_pendaftaran, status_verifikasi, catatan_verifikasi |
| **Metadata** | email_verified_at, verified_by, verified_at |
| **Timestamps** | created_at, updated_at, deleted_at |

**Indexes:** 8 indexes untuk optimal performance

See [DATABASE_SCHEMA.md](./DATABASE_SCHEMA.md) for full details.

---

## 🚀 Usage

### Registrasi Mahasiswa

#### Sarjana
```
http://localhost:8000/sarjana
```
**Required Fields:**
- Data pribadi lengkap
- NIK (WNI) atau Passport (WNA)
- Alamat lengkap
- Jalur program (RPL/Non RPL)
- Jenjang (D3/D4/S1)
- Program studi

#### Magister
```
http://localhost:8000/magister
```
**Required Fields:**
- Data pribadi lengkap
- Status kawin
- NIK (WNI)

#### Doktoral
```
http://localhost:8000/doktoral
```
**Required Fields:**
- Data pribadi lengkap
- Status kawin
- NIK (WNI)

### Query Data

```php
use App\Models\Mahasiswa;

// Get all mahasiswa
$all = Mahasiswa::all();

// Filter by type
$sarjana = Mahasiswa::sarjana()->get();
$magister = Mahasiswa::magister()->get();
$doktoral = Mahasiswa::doktoral()->get();

// Filter by status
$pending = Mahasiswa::pending()->get();
$verified = Mahasiswa::verified()->get();

// Kombinasi
$sarjanaPending = Mahasiswa::sarjana()->pending()->latest()->get();

// Statistics
$stats = [
    'total' => Mahasiswa::count(),
    'sarjana' => Mahasiswa::sarjana()->count(),
    'pending' => Mahasiswa::pending()->count(),
];
```

---

## 📚 Documentation

| File | Description |
|------|-------------|
| [CAPTCHA_IMPLEMENTATION.md](./CAPTCHA_IMPLEMENTATION.md) | Detail implementasi CAPTCHA |
| [CAPTCHA_SUMMARY.md](./CAPTCHA_SUMMARY.md) | Ringkasan fitur CAPTCHA |
| [DATABASE_SCHEMA.md](./DATABASE_SCHEMA.md) | Full database documentation |
| [DATABASE_ERD.md](./DATABASE_ERD.md) | Visual ERD diagram |
| [MODEL_MIGRATION_SUMMARY.md](./MODEL_MIGRATION_SUMMARY.md) | Model & Migration summary |
| [QUICK_REFERENCE.md](./QUICK_REFERENCE.md) | Quick reference guide |

---

## 🧪 Testing

### Run Seeder
```bash
php artisan db:seed --class=MahasiswaSeeder
```

**Generated:**
- 93 sample mahasiswa
- 3 test accounts dengan credentials

### Test Accounts
```
Email: test.sarjana@example.com
Password: password

Email: test.magister@example.com
Password: password

Email: test.wna@example.com
Password: password
```

### Tinker Commands
```bash
php artisan tinker

>>> Mahasiswa::count()
>>> Mahasiswa::sarjana()->count()
>>> Mahasiswa::first()->formatted_nama
>>> Mahasiswa::pending()->latest()->get()
```

---

## 🔍 API Reference

### Model Methods

```php
// Scopes
Mahasiswa::sarjana()
Mahasiswa::magister()
Mahasiswa::doktoral()
Mahasiswa::pending()
Mahasiswa::verified()
Mahasiswa::statusVerifikasi('rejected')
Mahasiswa::jenisPendaftaran('sarjana')

// Helper Methods
$mahasiswa->isWNI()           // boolean
$mahasiswa->isWNA()           // boolean
$mahasiswa->formatted_nama    // string
$mahasiswa->age               // integer
$mahasiswa->full_location     // string
```

### Routes

| Method | URI | Controller | Description |
|--------|-----|------------|-------------|
| GET | `/sarjana` | SarjanaController@show | Form Sarjana |
| POST | `/sarjana/submit` | SarjanaController@submit | Submit Sarjana |
| GET | `/sarjana/captcha/refresh` | SarjanaController@generateCaptcha | Refresh CAPTCHA |
| GET | `/magister` | MagisterController@index | Form Magister |
| POST | `/magister/submit` | MagisterController@submit | Submit Magister |
| GET | `/magister/captcha/refresh` | MagisterController@generateCaptcha | Refresh CAPTCHA |
| GET | `/doktoral` | DoktoralController@index | Form Doktoral |
| POST | `/doktoral/submit` | DoktoralController@submit | Submit Doktoral |
| GET | `/doktoral/captcha/refresh` | DoktoralController@generateCaptcha | Refresh CAPTCHA |

---

## 📊 Statistics

### Current Database
```
Total Mahasiswa:    93
├─ Sarjana:         47
├─ Magister:        31
└─ Doktoral:        15

Status:
├─ Pending:         ~50
├─ Verified:        ~30
└─ Rejected:        ~13

Citizenship:
├─ WNI:             ~75
└─ WNA:             ~18
```

---

## 🔧 Maintenance

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Fresh Install
```bash
php artisan migrate:fresh --seed
```

### Backup Database
```bash
php artisan backup:run  # if backup package installed
# or use mysqldump
mysqldump -u username -p database_name > backup.sql
```

---

## 🎯 Roadmap

### Phase 1 - Current ✅
- [x] Registration forms (3 types)
- [x] Custom CAPTCHA implementation
- [x] Database model & migration
- [x] Factory & seeder
- [x] Basic validation

### Phase 2 - Next
- [ ] Admin dashboard
- [ ] Mahasiswa verification system
- [ ] Email notifications
- [ ] Export to Excel/PDF
- [ ] Search & filter UI

### Phase 3 - Future
- [ ] Authentication system
- [ ] Student portal
- [ ] Document upload
- [ ] Payment integration
- [ ] API endpoints

---

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## 📝 License

This project is licensed under the MIT License.

---

## 👥 Team

**SALUT Insan Cendekia Development Team**

---

## 📞 Support

- **Email:** support@salutinsancendekia.ac.id
- **Website:** https://salutinsancendekia.ac.id
- **Documentation:** See docs folder

---

## 🙏 Acknowledgments

- Laravel Framework
- Bootstrap
- Font Awesome
- jQuery
- All contributors

---

## 📈 Version History

### v1.0.0 (2026-01-03)
- ✅ Initial release
- ✅ 3 registration forms
- ✅ Custom CAPTCHA
- ✅ Complete database model
- ✅ 93 sample data

---

**Built with ❤️ using Laravel**

**Last Updated:** January 3, 2026
