# Custom Arithmetic CAPTCHA - Implementasi

## Deskripsi
Implementasi Custom Arithmetic CAPTCHA pada form registrasi Magister tanpa menggunakan library pihak ketiga, API eksternal, atau Google reCAPTCHA.

## Fitur yang Diimplementasikan

### ✅ 1. Generate Dua Angka Acak di Server
- Lokasi: `MagisterController::generateCaptcha()`
- Menghasilkan dua angka random antara 1-50
- Disimpan di session PHP

### ✅ 2. Simpan Hasil Penjumlahan di Session
- Variabel session:
  - `captcha_num1`: Angka pertama
  - `captcha_num2`: Angka kedua  
  - `captcha_result`: Hasil penjumlahan
- Session otomatis di-manage oleh Laravel

### ✅ 3. Tampilkan Soal di Blade
- Lokasi: `resources/views/pages/magister.blade.php`
- Menggunakan syntax Blade: `{{ session('captcha_num1') }} + {{ session('captcha_num2') }}`
- Ditampilkan dalam box dengan styling Bootstrap

### ✅ 4. Input Jawaban CAPTCHA
- Input field untuk user memasukkan jawaban
- Validasi HTML5 (required, type="number")
- Styling responsif dengan Bootstrap

### ✅ 5. Validasi Jawaban di Controller
- Lokasi: `MagisterController::submit()`
- Membandingkan jawaban user dengan `session('captcha_result')`
- Jika salah, tampilkan error dan generate CAPTCHA baru
- Jika benar, lanjutkan proses registrasi

### ✅ 6. Pesan Error
- Error handling dengan Laravel Validator
- Menampilkan pesan error spesifik untuk CAPTCHA
- Pesan error: "Jawaban CAPTCHA salah! Silakan coba lagi."

### ✅ 7. Tombol Refresh CAPTCHA (AJAX)
- Tombol refresh dengan icon Font Awesome
- Request AJAX ke route `/magister/captcha/refresh`
- Update soal CAPTCHA tanpa reload halaman
- Loading state (spinning icon) saat fetch

### ✅ 8. Hapus Session CAPTCHA
- Session dihapus setelah submit form (benar/salah)
- Menggunakan `session()->forget()`
- Generate CAPTCHA baru untuk percobaan berikutnya

## Struktur File

```
app/Http/Controllers/
└── MagisterController.php         # Controller utama dengan logic CAPTCHA

routes/
└── web.php                         # Route definitions

resources/views/pages/
└── magister.blade.php              # View form registrasi dengan CAPTCHA
```

## Route Endpoints

| Method | URL | Fungsi |
|--------|-----|--------|
| GET | `/magister` | Tampilkan form registrasi |
| POST | `/magister/submit` | Submit form dan validasi |
| GET | `/magister/captcha/refresh` | Generate CAPTCHA baru (AJAX) |

## Cara Kerja

### Flow Registrasi:
1. User mengakses `/magister`
2. Controller generate CAPTCHA dan simpan di session
3. CAPTCHA ditampilkan di form
4. User isi form dan jawab CAPTCHA
5. User klik Submit
6. Controller validasi semua input termasuk CAPTCHA
7. Jika CAPTCHA salah:
   - Tampilkan error
   - Generate CAPTCHA baru
   - Kembalikan ke form dengan data lama
8. Jika CAPTCHA benar:
   - Hapus session CAPTCHA
   - Proses registrasi
   - Redirect dengan success message

### Flow Refresh CAPTCHA:
1. User klik tombol "Refresh"
2. JavaScript kirim AJAX request ke `/magister/captcha/refresh`
3. Controller generate angka baru dan simpan di session
4. Return JSON dengan soal baru
5. JavaScript update tampilan CAPTCHA
6. Input jawaban di-clear

## Keamanan

### Implementasi Keamanan:
- ✅ CAPTCHA di-generate di server (tidak bisa di-bypass client-side)
- ✅ Jawaban disimpan di session (tidak terexpose ke client)
- ✅ CSRF protection dengan `@csrf` token
- ✅ Session di-clear setelah submit
- ✅ Validasi server-side yang ketat
- ✅ Tidak ada dependency eksternal

### Catatan Keamanan:
- Session menggunakan cookie encryption Laravel
- Rate limiting bisa ditambahkan di route middleware
- CAPTCHA auto-refresh setiap submit (mencegah replay attack)

## Testing

### Manual Testing:
1. Akses `http://localhost/magister`
2. Isi form dengan data valid
3. Jawab CAPTCHA dengan benar → Harus success
4. Jawab CAPTCHA dengan salah → Harus error
5. Klik refresh → Soal harus berubah tanpa reload
6. Submit lagi → Session lama harus hilang

## Customisasi

### Mengubah Range Angka:
Edit di `MagisterController::generateCaptcha()`:
```php
$num1 = rand(1, 50);  // Ubah 1 dan 50 sesuai kebutuhan
$num2 = rand(1, 50);
```

### Mengubah Operasi (Contoh: Pengurangan):
```php
// Di Controller
$result = $num1 - $num2;

// Di Blade
{{ session('captcha_num1') }} - {{ session('captcha_num2') }}
```

### Menambahkan Multiple Operations:
```php
$operations = ['+', '-', '*'];
$operation = $operations[array_rand($operations)];
session(['captcha_operation' => $operation]);
```

## Requirements

- PHP >= 8.0
- Laravel >= 10.x
- Session driver configured
- Bootstrap CSS/JS (untuk styling)
- Font Awesome (untuk icons)

## Troubleshooting

### CAPTCHA tidak berubah saat refresh:
- Pastikan session driver configured correctly
- Check browser console untuk error AJAX
- Verify route `magister.captcha.refresh` terdaftar

### Session tidak tersimpan:
- Check `config/session.php`
- Pastikan session driver tidak `array` (gunakan `file` atau `database`)
- Clear cache: `php artisan config:clear`

### Error 419 (CSRF):
- Pastikan `@csrf` ada di form
- Check `VerifyCsrfToken` middleware
- Pastikan cookie tidak di-block browser

## Future Improvements

Saran pengembangan lebih lanjut:
- [ ] Tambahkan audio CAPTCHA untuk aksesibilitas
- [ ] Implementasi rate limiting per IP
- [ ] Log failed attempts untuk security monitoring
- [ ] Multiple CAPTCHA types (penjumlahan, pengurangan, perkalian)
- [ ] CAPTCHA difficulty levels
- [ ] Session timeout untuk CAPTCHA

## Lisensi
Implementasi ini mengikuti lisensi project utama.
