<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Mahasiswa;

class MagisterController extends Controller
{
    /**
     * Tampilkan form registrasi magister
     */
    public function index()
    {
        // Generate CAPTCHA baru saat pertama kali load
        $this->generateCaptcha();
        return view('pages.magister');
    }

    /**
     * Generate CAPTCHA dan simpan di session
     */
    public function generateCaptcha()
    {
        // Generate dua angka acak antara 1-50
        $num1 = rand(1, 50);
        $num2 = rand(1, 50);
        
        // Hitung hasil penjumlahan
        $result = $num1 + $num2;
        
        // Simpan di session
        session([
            'captcha_num1' => $num1,
            'captcha_num2' => $num2,
            'captcha_result' => $result
        ]);
        
        return response()->json([
            'success' => true,
            'question' => $num1 . ' + ' . $num2
        ]);
    }

    /**
     * Submit form registrasi
     */
    public function submit(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:-15 years',
            'agama' => 'required|in:Islam,Protestan,Katolik,Hindu,Budha,Konghucu',
            'jenis_kelamin' => 'required|in:L,P',
            'status_kawin' => 'required|in:Kawin,Belum Kawin',
            'nik' => 'required|digits:16|unique:mahasiswa,nik',
            'nama_ibu' => 'required|string|max:255',
            'kewarganegaraan' => 'required|in:WNI,WNA',
            'no_hp' => 'required|numeric|digits_between:10,15',
            'email' => 'required|email|unique:mahasiswa,email',
            'password' => 'required|min:6|confirmed',
            'captcha_answer' => 'required|numeric'
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.before' => 'Usia minimal 15 tahun',
            'agama.required' => 'Agama wajib dipilih',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'status_kawin.required' => 'Status kawin wajib dipilih',
            'nik.required' => 'NIK wajib diisi',
            'nik.digits' => 'NIK harus 16 digit',
            'nik.unique' => 'NIK sudah terdaftar',
            'nama_ibu.required' => 'Nama ibu kandung wajib diisi',
            'kewarganegaraan.required' => 'Kewarganegaraan wajib dipilih',
            'no_hp.required' => 'Nomor HP wajib diisi',
            'no_hp.digits_between' => 'Nomor HP harus antara 10-15 digit',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'captcha_answer.required' => 'Jawaban CAPTCHA wajib diisi',
            'captcha_answer.numeric' => 'Jawaban CAPTCHA harus berupa angka'
        ]);

        // Cek validasi form
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validasi CAPTCHA
        $captchaResult = session('captcha_result');
        $userAnswer = (int) $request->captcha_answer;

        if (!$captchaResult || $userAnswer !== $captchaResult) {
            // Hapus session CAPTCHA lama
            session()->forget(['captcha_num1', 'captcha_num2', 'captcha_result']);
            
            // Generate CAPTCHA baru
            $this->generateCaptcha();
            
            return redirect()->back()
                ->withErrors(['captcha_answer' => 'Jawaban CAPTCHA salah! Silakan coba lagi.'])
                ->withInput($request->except('captcha_answer'));
        }

        // Jika CAPTCHA benar, hapus session CAPTCHA
        session()->forget(['captcha_num1', 'captcha_num2', 'captcha_result']);

        /* ===============================
           SIMPAN KE DATABASE
        =============================== */
        try {
            // Persiapkan data untuk disimpan (ambil semua dari request)
            $data = $request->except(['captcha_answer', 'password_confirmation']);
            
            // Set jenis pendaftaran
            $data['jenis_pendaftaran'] = 'magister';
            
            // Hash password
            $data['password'] = Hash::make($request->password);
            
            // Set default status
            $data['status_verifikasi'] = 'pending';
            
            // Simpan ke database
            $mahasiswa = Mahasiswa::create($data);
            
            return redirect()->route('magister')
                ->with('success', 'Pendaftaran berhasil! Data Anda telah tersimpan dengan No. Registrasi: ' . $mahasiswa->id);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }
}
