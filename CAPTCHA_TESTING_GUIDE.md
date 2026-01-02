# Testing Guide - Custom Arithmetic CAPTCHA

## Prerequisites
Pastikan Laravel development server sudah berjalan:
```bash
php artisan serve
```

Atau jika menggunakan Laravel Sail:
```bash
sail up
```

## Test Case 1: Load Form dengan CAPTCHA Baru

### Steps:
1. Buka browser dan akses: `http://localhost:8000/magister`
2. Scroll ke bagian CAPTCHA

### Expected Result:
✅ Muncul soal penjumlahan (contoh: "15 + 23")  
✅ Input field untuk jawaban tersedia  
✅ Tombol "Refresh" dengan icon tersedia  
✅ Soal berubah-ubah setiap reload halaman

### Actual Result:
[ ] Pass
[ ] Fail - Note: _______________________

---

## Test Case 2: Refresh CAPTCHA Tanpa Reload

### Steps:
1. Akses form registrasi
2. Catat soal CAPTCHA saat ini (contoh: "12 + 8")
3. Klik tombol "Refresh" (icon refresh)
4. Tunggu hingga icon selesai spinning

### Expected Result:
✅ Icon refresh berputar saat loading  
✅ Soal CAPTCHA berubah tanpa reload halaman  
✅ Input jawaban otomatis ter-clear  
✅ Tidak ada error di browser console

### Actual Result:
[ ] Pass
[ ] Fail - Note: _______________________

---

## Test Case 3: Submit dengan CAPTCHA Benar

### Steps:
1. Isi semua field form dengan data valid:
   - Nama: John Doe
   - Tempat Lahir: Jakarta
   - Tanggal Lahir: 01/01/2000
   - Agama: Islam
   - Jenis Kelamin: L
   - Status Kawin: Belum Kawin
   - NIK: 1234567890123456
   - Nama Ibu: Jane Doe
   - Kewarganegaraan: WNI
   - No HP: 081234567890
   - Email: john@example.com
   - Password: password123
   - Confirm Password: password123
2. Lihat soal CAPTCHA (contoh: "10 + 5")
3. Masukkan jawaban yang **BENAR** (15)
4. Klik "Submit"

### Expected Result:
✅ Form berhasil disubmit  
✅ Muncul success message: "Pendaftaran berhasil! Data Anda telah tersimpan."  
✅ Session CAPTCHA lama sudah dihapus  
✅ CAPTCHA baru di-generate (jika kembali ke form)

### Actual Result:
[ ] Pass
[ ] Fail - Note: _______________________

---

## Test Case 4: Submit dengan CAPTCHA Salah

### Steps:
1. Isi semua field form dengan data valid
2. Lihat soal CAPTCHA (contoh: "15 + 7")
3. Masukkan jawaban yang **SALAH** (contoh: "20" padahal harusnya "22")
4. Klik "Submit"

### Expected Result:
✅ Form tidak tersubmit  
✅ Muncul error message: "Jawaban CAPTCHA salah! Silakan coba lagi."  
✅ Soal CAPTCHA berubah (bukan soal yang sama)  
✅ Data form lain tetap tersimpan (tidak hilang)  
✅ Input CAPTCHA di-clear

### Actual Result:
[ ] Pass
[ ] Fail - Note: _______________________

---

## Test Case 5: Submit dengan CAPTCHA Kosong

### Steps:
1. Isi semua field form dengan data valid
2. **Kosongkan** input jawaban CAPTCHA
3. Klik "Submit"

### Expected Result:
✅ Muncul HTML5 validation "Please fill out this field"  
✅ Form tidak tersubmit  
✅ Error validation: "Jawaban CAPTCHA wajib diisi"

### Actual Result:
[ ] Pass
[ ] Fail - Note: _______________________

---

## Test Case 6: Submit dengan Input Non-Numeric

### Steps:
1. Isi semua field form dengan data valid
2. Masukkan jawaban CAPTCHA dengan huruf (contoh: "abc")
3. Klik "Submit"

### Expected Result:
✅ Input type="number" akan mencegah input huruf  
✅ Atau jika lolos, muncul error: "Jawaban CAPTCHA harus berupa angka"

### Actual Result:
[ ] Pass
[ ] Fail - Note: _______________________

---

## Test Case 7: Session CAPTCHA Persistence

### Steps:
1. Akses form registrasi
2. Catat soal CAPTCHA (contoh: "8 + 12")
3. **Refresh halaman (F5)**
4. Perhatikan soal CAPTCHA

### Expected Result:
✅ Soal CAPTCHA berubah (session lama dihapus)  
✅ Generate CAPTCHA baru setiap page load

### Actual Result:
[ ] Pass
[ ] Fail - Note: _______________________

---

## Test Case 8: Multiple Refresh CAPTCHA

### Steps:
1. Akses form registrasi
2. Klik tombol "Refresh" 5 kali berturut-turut
3. Perhatikan setiap perubahan soal

### Expected Result:
✅ Setiap klik refresh menghasilkan soal baru  
✅ Tidak ada error atau hang  
✅ Icon spinning bekerja dengan baik  
✅ Soal selalu berbeda-beda (random)

### Actual Result:
[ ] Pass
[ ] Fail - Note: _______________________

---

## Test Case 9: Security - Inspect Element Attack

### Steps:
1. Akses form registrasi
2. Lihat soal CAPTCHA (contoh: "14 + 9")
3. Buka Developer Tools (F12)
4. Inspect element CAPTCHA
5. Coba cari jawabannya di HTML/JavaScript
6. Submit form dengan jawaban yang ditemukan

### Expected Result:
✅ Jawaban CAPTCHA **TIDAK** ditemukan di client-side  
✅ Tidak ada variabel JavaScript berisi jawaban  
✅ Session data tidak terexpose  
✅ Harus tetap masukkan jawaban yang benar

### Actual Result:
[ ] Pass
[ ] Fail - Note: _______________________

---

## Test Case 10: AJAX Error Handling

### Steps:
1. Akses form registrasi
2. Buka Developer Tools > Network tab
3. Set network throttling ke "Offline"
4. Klik tombol "Refresh" CAPTCHA
5. Perhatikan response

### Expected Result:
✅ Muncul alert: "Gagal memuat CAPTCHA baru. Silakan coba lagi."  
✅ Button kembali enabled setelah error  
✅ Icon tidak stuck spinning  
✅ Console log error dengan detail

### Actual Result:
[ ] Pass
[ ] Fail - Note: _______________________

---

## Browser Compatibility Testing

Test di berbagai browser:

### Chrome/Edge
- [ ] Pass
- [ ] Fail - Note: _______________________

### Firefox
- [ ] Pass
- [ ] Fail - Note: _______________________

### Safari
- [ ] Pass
- [ ] Fail - Note: _______________________

---

## Performance Testing

### Load Time:
- [ ] CAPTCHA generate < 100ms
- [ ] AJAX refresh < 200ms
- [ ] Form submit validation < 500ms

### Memory:
- [ ] No memory leaks setelah multiple refresh
- [ ] Session storage stabil

---

## Security Checklist

- [ ] CSRF token implemented
- [ ] CAPTCHA result disimpan di server (session)
- [ ] Tidak ada hardcoded answer di client
- [ ] Session cleared setelah submit
- [ ] Input validation di server-side
- [ ] Rate limiting (opsional - future improvement)

---

## Common Issues & Troubleshooting

### Issue: CAPTCHA tidak berubah saat refresh
**Solution:**
1. Check browser console untuk error
2. Verify route terdaftar: `php artisan route:list | grep captcha`
3. Clear config cache: `php artisan config:clear`
4. Check session driver di `.env`

### Issue: Error 419 CSRF token mismatch
**Solution:**
1. Pastikan `@csrf` ada di form
2. Clear browser cookies
3. Restart Laravel server

### Issue: Session tidak tersimpan
**Solution:**
1. Check `SESSION_DRIVER` di `.env` (gunakan `file` bukan `array`)
2. Pastikan folder `storage/framework/sessions/` writable
3. Run: `php artisan session:table && php artisan migrate` jika pakai database

### Issue: AJAX fetch error
**Solution:**
1. Check network tab untuk detail error
2. Verify route URL correct
3. Ensure JSON response format correct

---

## Automated Testing (Optional)

Jika ingin membuat automated test, buat file:
`tests/Feature/CaptchaTest.php`

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaptchaTest extends TestCase
{
    public function test_captcha_generates_on_page_load()
    {
        $response = $this->get('/magister');
        
        $response->assertStatus(200);
        $this->assertTrue(session()->has('captcha_num1'));
        $this->assertTrue(session()->has('captcha_num2'));
        $this->assertTrue(session()->has('captcha_result'));
    }
    
    public function test_captcha_refresh_returns_json()
    {
        $response = $this->getJson('/magister/captcha/refresh');
        
        $response->assertStatus(200)
                 ->assertJson(['success' => true])
                 ->assertJsonStructure(['question']);
    }
    
    public function test_form_submission_with_correct_captcha()
    {
        session(['captcha_result' => 10]);
        
        $response = $this->post('/magister/submit', [
            // ... form data
            'captcha_answer' => 10
        ]);
        
        $response->assertSessionHas('success');
        $this->assertFalse(session()->has('captcha_result'));
    }
    
    public function test_form_submission_with_wrong_captcha()
    {
        session(['captcha_result' => 10]);
        
        $response = $this->post('/magister/submit', [
            // ... form data
            'captcha_answer' => 999
        ]);
        
        $response->assertSessionHasErrors('captcha_answer');
    }
}
```

Run test:
```bash
php artisan test --filter CaptchaTest
```

---

## Test Report Summary

| Test Case | Status | Notes |
|-----------|--------|-------|
| 1. Load Form | [ ] | |
| 2. Refresh | [ ] | |
| 3. Submit Benar | [ ] | |
| 4. Submit Salah | [ ] | |
| 5. Submit Kosong | [ ] | |
| 6. Non-Numeric | [ ] | |
| 7. Session | [ ] | |
| 8. Multiple Refresh | [ ] | |
| 9. Security | [ ] | |
| 10. Error Handling | [ ] | |

**Overall Status:** [ ] All Pass [ ] Some Fail

**Tester:** _______________________  
**Date:** _______________________  
**Environment:** _______________________
