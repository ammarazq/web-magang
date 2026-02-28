<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa;
use App\Models\User;

class DoktoralController extends Controller
{
    /**
     * Tampilkan form registrasi doktoral
     */
    public function index()
    {
        // Generate CAPTCHA baru saat pertama kali load
        $this->generateCaptcha();
        return view('pages.doktoral');
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
            'captcha_doktoral_num1' => $num1,
            'captcha_doktoral_num2' => $num2,
            'captcha_doktoral_result' => $result
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
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[!@#$%^&*()_+\-=\[\]{};:\'",.<>\/?\\|`~]/',
                function ($attribute, $value, $fail) {
                    // Cek urutan angka
                    for ($i = 0; $i < strlen($value) - 2; $i++) {
                        if (is_numeric($value[$i]) && is_numeric($value[$i+1]) && is_numeric($value[$i+2])) {
                            $num1 = (int)$value[$i];
                            $num2 = (int)$value[$i+1];
                            $num3 = (int)$value[$i+2];
                            
                            if (($num2 === $num1 + 1 && $num3 === $num2 + 1) || 
                                ($num2 === $num1 - 1 && $num3 === $num2 - 1)) {
                                $fail('Password tidak boleh mengandung angka berurutan (contoh: 123, 234, 321).');
                                return;
                            }
                        }
                    }
                    
                    // Cek urutan huruf
                    $lower = strtolower($value);
                    for ($i = 0; $i < strlen($lower) - 2; $i++) {
                        if (ctype_alpha($lower[$i]) && ctype_alpha($lower[$i+1]) && ctype_alpha($lower[$i+2])) {
                            $char1 = ord($lower[$i]);
                            $char2 = ord($lower[$i+1]);
                            $char3 = ord($lower[$i+2]);
                            
                            if (($char2 === $char1 + 1 && $char3 === $char2 + 1) || 
                                ($char2 === $char1 - 1 && $char3 === $char2 - 1)) {
                                $fail('Password tidak boleh mengandung huruf berurutan (contoh: abc, xyz, cba).');
                                return;
                            }
                        }
                    }
                }
            ],
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
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter spesial',
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
        $captchaResult = session('captcha_doktoral_result');
        $userAnswer = (int) $request->captcha_answer;

        if (!$captchaResult || $userAnswer !== $captchaResult) {
            // Hapus session CAPTCHA lama
            session()->forget(['captcha_doktoral_num1', 'captcha_doktoral_num2', 'captcha_doktoral_result']);
            
            // Generate CAPTCHA baru
            $this->generateCaptcha();
            
            return redirect()->back()
                ->withErrors(['captcha_answer' => 'Jawaban CAPTCHA salah! Silakan coba lagi.'])
                ->withInput($request->except('captcha_answer'));
        }

        // Jika CAPTCHA benar, hapus session CAPTCHA
        session()->forget(['captcha_doktoral_num1', 'captcha_doktoral_num2', 'captcha_doktoral_result']);

        /* ===============================
           SIMPAN KE DATABASE
        =============================== */
        try {
            // Gunakan database transaction untuk memastikan data konsisten
            DB::beginTransaction();
            
            // 1. BUAT AKUN USER TERLEBIH DAHULU
            $user = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            
            // 2. BUAT DATA MAHASISWA DAN HUBUNGKAN DENGAN USER
            // Persiapkan data untuk disimpan (ambil semua dari request)
            $data = $request->except(['captcha_answer', 'password_confirmation']);
            
            // Set jenis pendaftaran
            $data['jenis_pendaftaran'] = 'doktoral';
            
            // Hash password
            $data['password'] = Hash::make($request->password);
            
            // Set default status
            $data['status_verifikasi'] = 'pending';
            
            // Set user_id untuk relasi
            $data['user_id'] = $user->id;
            
            // Simpan ke database
            $mahasiswa = Mahasiswa::create($data);
            
            // Commit transaction jika semua berhasil
            DB::commit();
            
            return redirect()->route('doktoral')
                ->with('success', 'Pendaftaran berhasil! Data Anda telah tersimpan dengan No. Registrasi: ' . $mahasiswa->id . '. Anda sudah bisa login dengan email dan password yang telah didaftarkan.');
        } catch (\Exception $e) {
            // Rollback jika terjadi error
            DB::rollBack();
            
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }

    /**
     * Tampilkan form upload dokumen doktoral
     */
    public function uploadForm()
    {
        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        if (!$mahasiswa) {
            return redirect()->route('login')->with('error', 'Data mahasiswa tidak ditemukan');
        }

        // Ambil atau buat data dokumen
        $dokumen = $mahasiswa->dokumen ?? new \App\Models\DokumenMahasiswa(['mahasiswa_id' => $mahasiswa->id]);
        
        return view('pages.doktoral-upload', compact('mahasiswa', 'dokumen'));
    }

    /**
     * Proses upload dokumen doktoral
     */
    public function uploadDokumen(Request $request)
    {
        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        if (!$mahasiswa) {
            return redirect()->route('login')->with('error', 'Data mahasiswa tidak ditemukan');
        }

        // Validasi file upload
        $validator = Validator::make($request->all(), [
            'formulir_pendaftaran' => 'nullable|file|mimes:jpg,jpeg,pdf|max:5120',
            'formulir_keabsahan' => 'nullable|file|mimes:jpg,jpeg,pdf|max:5120',
            'foto_formal' => 'nullable|file|mimes:jpg,jpeg|max:2048',
            'ktp' => 'nullable|file|mimes:jpg,jpeg|max:2048',
            'ijazah_slta' => 'nullable|file|mimes:pdf|max:5120',
            'sertifikat_akreditasi_prodi' => 'nullable|file|mimes:pdf|max:5120',
            'transkrip_d3_d4_s1' => 'nullable|file|mimes:pdf|max:5120',
            'sertifikat_toefl' => 'nullable|file|mimes:pdf|max:5120',
            'rancangan_penelitian' => 'nullable|file|mimes:pdf|max:5120',
            'sk_mampu_komputer' => 'nullable|file|mimes:pdf|max:5120',
            'bukti_tes_tpa' => 'nullable|file|mimes:pdf|max:5120',
            'seleksi_tes_substansi' => 'nullable|file|mimes:pdf|max:5120',
            'formulir_isian_foto' => 'nullable|file|mimes:jpg,jpeg,pdf|max:2048',
            'riwayat_hidup' => 'nullable|file|mimes:pdf|max:5120',
            'ijazah_s2' => 'nullable|file|mimes:pdf|max:5120',
            'transkrip_s2' => 'nullable|file|mimes:pdf|max:5120',
            'sertifikat_akreditasi_s2' => 'nullable|file|mimes:pdf|max:5120',
            'berkas_dokumen_pendaftaran' => 'nullable|file|mimes:pdf|max:5120',
        ], [
            '*.mimes' => 'File :attribute harus berformat :values',
            '*.max' => 'Ukuran file :attribute terlalu besar. Maksimal :max KB (5MB untuk PDF, 2MB untuk gambar)',
            '*.file' => 'File :attribute tidak valid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Ambil atau buat data dokumen
            $dokumen = $mahasiswa->dokumen;
            if (!$dokumen) {
                $dokumen = new \App\Models\DokumenMahasiswa();
                $dokumen->mahasiswa_id = $mahasiswa->id;
            }
            
            // Upload file yang ada
            $uploadedFiles = [];
            $fields = [
                'formulir_pendaftaran', 'formulir_keabsahan', 'foto_formal', 'ktp', 'ijazah_slta',
                'sertifikat_akreditasi_prodi', 'transkrip_d3_d4_s1', 'sertifikat_toefl',
                'rancangan_penelitian', 'sk_mampu_komputer', 'bukti_tes_tpa',
                'seleksi_tes_substansi', 'formulir_isian_foto', 'riwayat_hidup',
                'ijazah_s2', 'transkrip_s2', 'sertifikat_akreditasi_s2', 'berkas_dokumen_pendaftaran'
            ];

            foreach ($fields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $fileName = $mahasiswa->id . '_' . $field . '_' . time() . '.' . $file->getClientOriginalExtension();
                    // Simpan langsung ke public/dokumen_mahasiswa
                    $file->move(public_path('dokumen_mahasiswa'), $fileName);
                    $dokumen->$field = $fileName;
                    $uploadedFiles[] = $field;
                }
            }

            // Update status dokumen
            if (count($uploadedFiles) > 0) {
                // Cek apakah dokumen sudah lengkap
                if ($dokumen->isDokumenLengkap()) {
                    $dokumen->status_dokumen = 'lengkap';
                }
                $dokumen->save();
                return redirect()->back()->with('success', 'Berhasil mengupload ' . count($uploadedFiles) . ' dokumen');
            } else {
                return redirect()->back()->with('info', 'Tidak ada dokumen yang diupload');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
