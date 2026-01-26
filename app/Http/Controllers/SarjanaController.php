<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Mahasiswa;
use App\Models\User;

class SarjanaController extends Controller
{
    /**
     * Tampilkan form registrasi sarjana
     */
    public function show()
    {
        // Generate CAPTCHA baru saat pertama kali load
        $this->generateCaptcha();
        return view('pages.sarjana');
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
            'captcha_sarjana_num1' => $num1,
            'captcha_sarjana_num2' => $num2,
            'captcha_sarjana_result' => $result
        ]);
        
        return response()->json([
            'success' => true,
            'question' => $num1 . ' + ' . $num2
        ]);
    }

    /**
     * Proses submit form registrasi sarjana
     */
    public function submit(Request $request)
    {
        /* ===============================
           VALIDASI DATA UTAMA
        =============================== */
        $rules = [
            'nama_lengkap'    => 'required|string|max:255',
            'tempat_lahir'    => 'required|string|max:255',
            'tanggal_lahir'   => [
                'required',
                'date',
                'before:' . now()->subYears(15)->format('Y-m-d'),
                function ($attr, $value, $fail) {
                    $birthDate = Carbon::parse($value);
                    $age = $birthDate->age;
                    
                    if ($age < 15) {
                        $fail('Usia minimal pendaftar adalah 15 tahun. Usia Anda saat ini: ' . $age . ' tahun.');
                    }
                }
            ],
            'jenis_kelamin'   => 'required|in:L,P',
            'nama_ibu'        => 'required|string|max:255',
            'agama'           => 'required|in:Islam,Protestan,Katolik,Hindu,Budha,Konghucu',
            'alamat'          => 'required|string',
            'kewarganegaraan' => 'required|in:WNI,WNA',
            'jalur_program'   => 'required|in:RPL,Non RPL',
            'jenjang'         => 'required|in:D3,D4,S1',
            'program_studi'   => 'required|string',
            'no_hp'           => 'required|numeric|digits_between:10,15',
            'email'           => 'required|email|max:255|unique:mahasiswa,email',
            'password'        => [
                'required',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',      // minimal 1 huruf kecil
                'regex:/[A-Z]/',      // minimal 1 huruf besar
                'regex:/[0-9]/',      // minimal 1 angka
                'regex:/[!@#$%^&*()_+\-=\[\]{};:\'",.<>\/?\\|`~]/', // minimal 1 karakter spesial
                function ($attribute, $value, $fail) {
                    // Cek urutan angka (123, 234, 321, dst)
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
                    
                    // Cek urutan huruf (abc, bcd, xyz, dst)
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
            'captcha_answer'  => 'required|numeric',
        ];

        // Pesan validasi kustom
        $messages = [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.before' => 'Usia minimal pendaftar adalah 15 tahun.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
            'nama_ibu.required' => 'Nama ibu kandung wajib diisi.',
            'agama.required' => 'Agama wajib dipilih.',
            'agama.in' => 'Agama yang dipilih tidak valid.',
            'alamat.required' => 'Alamat wajib diisi.',
            'kewarganegaraan.required' => 'Kewarganegaraan wajib dipilih.',
            'kewarganegaraan.in' => 'Kewarganegaraan tidak valid.',
            'jalur_program.required' => 'Jalur program wajib dipilih.',
            'jalur_program.in' => 'Jalur program tidak valid.',
            'jenjang.required' => 'Jenjang pendidikan wajib dipilih.',
            'jenjang.in' => 'Jenjang pendidikan tidak valid.',
            'program_studi.required' => 'Program studi wajib dipilih.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.numeric' => 'Nomor HP harus berupa angka.',
            'no_hp.digits_between' => 'Nomor HP harus antara 10-15 digit.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar, gunakan email lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter spesial.',
            'captcha_answer.required' => 'Jawaban captcha wajib diisi.',
            'captcha_answer.numeric' => 'Jawaban captcha harus berupa angka.',
        ];

        // Validasi kondisional untuk WNI
        if ($request->kewarganegaraan === 'WNI') {
            $rules['nik'] = [
                'required',
                'numeric',
                'digits:16',
                'unique:mahasiswa,nik'
            ];
            $messages['nik.required'] = 'NIK wajib diisi untuk WNI.';
            $messages['nik.numeric'] = 'NIK harus berupa angka.';
            $messages['nik.digits'] = 'NIK harus terdiri dari 16 digit.';
            $messages['nik.unique'] = 'NIK sudah terdaftar.';
        }

        // Validasi kondisional untuk WNA
        if ($request->kewarganegaraan === 'WNA') {
            $rules['negara'] = 'required|string|max:255';
            $rules['passport'] = [
                'required',
                'string',
                'min:6',
                'max:15',
                'unique:mahasiswa,passport'
            ];
            $messages['negara.required'] = 'Negara asal wajib dipilih untuk WNA.';
            $messages['passport.required'] = 'Nomor passport wajib diisi untuk WNA.';
            $messages['passport.min'] = 'Nomor passport minimal 6 karakter.';
            $messages['passport.max'] = 'Nomor passport maksimal 15 karakter.';
            $messages['passport.unique'] = 'Nomor passport sudah terdaftar.';
        }

        // Jalankan validasi
        $validated = $request->validate($rules, $messages);

        /* ===============================
           VALIDASI CAPTCHA
        =============================== */
        $captchaResult = session('captcha_sarjana_result');
        $userAnswer = (int) $request->captcha_answer;

        if (!$captchaResult || $userAnswer !== $captchaResult) {
            // Hapus session CAPTCHA lama
            session()->forget(['captcha_sarjana_num1', 'captcha_sarjana_num2', 'captcha_sarjana_result']);
            
            // Generate CAPTCHA baru
            $this->generateCaptcha();
            
            return redirect()->back()
                ->withErrors(['captcha_answer' => 'Jawaban CAPTCHA salah! Silakan coba lagi.'])
                ->withInput($request->except('captcha_answer'));
        }

        // Jika CAPTCHA benar, hapus session CAPTCHA
        session()->forget(['captcha_sarjana_num1', 'captcha_sarjana_num2', 'captcha_sarjana_result']);

        /* ===============================
           SIMPAN KE DATABASE
        =============================== */
        try {
            // Gunakan database transaction untuk memastikan data konsisten
            DB::beginTransaction();
            
            // 1. BUAT AKUN USER TERLEBIH DAHULU
            $user = User::create([
                'name' => $validated['nama_lengkap'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            
            // 2. BUAT DATA MAHASISWA DAN HUBUNGKAN DENGAN USER
            // Set jenis pendaftaran
            $validated['jenis_pendaftaran'] = 'sarjana';
            
            // Set default status
            $validated['status_verifikasi'] = 'pending';
            
            // Set user_id untuk relasi
            $validated['user_id'] = $user->id;
            
            // Hash password untuk mahasiswa juga (untuk backup)
            $validated['password'] = Hash::make($validated['password']);
            
            // Simpan ke database mahasiswa
            $mahasiswa = Mahasiswa::create($validated);
            
            // Commit transaction jika semua berhasil
            DB::commit();
            
            return redirect()->route('sarjana')
                ->with('success', 'Pendaftaran berhasil! Data Anda telah tersimpan dengan No. Registrasi: ' . $mahasiswa->id . '. Anda sudah bisa login dengan email dan password yang telah didaftarkan.');
        } catch (\Exception $e) {
            // Rollback jika terjadi error
            DB::rollBack();
            
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }

    /**
     * Tampilkan form upload dokumen sarjana (D3/D4/S1)
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
        
        // Tentukan view berdasarkan jenjang
        $jenjang = strtolower($mahasiswa->jenjang);
        $viewName = 'pages.' . $jenjang . '-upload';
        
        // Cek apakah view ada, jika tidak gunakan default
        if (!view()->exists($viewName)) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Halaman upload untuk jenjang ' . $mahasiswa->jenjang . ' belum tersedia');
        }
        
        return view($viewName, compact('mahasiswa', 'dokumen'));
    }

    /**
     * Proses upload dokumen sarjana (D3/D4/S1)
     */
    public function uploadDokumen(Request $request)
    {
        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        
        if (!$mahasiswa) {
            return redirect()->route('login')->with('error', 'Data mahasiswa tidak ditemukan');
        }

        // Validasi file upload berdasarkan jenjang
        $rules = [
            'formulir_pendaftaran' => 'nullable|file|mimes:jpg,jpeg,pdf|max:5120',
            'formulir_keabsahan' => 'nullable|file|mimes:jpg,jpeg,pdf|max:5120',
            'foto_formal' => 'nullable|file|mimes:jpg,jpeg|max:2048',
            'ktp' => 'nullable|file|mimes:jpg,jpeg|max:2048',
            'ijazah_slta' => 'nullable|file|mimes:pdf|max:5120',
        ];

        // Tambahan untuk S1 RPL
        if ($mahasiswa->jenjang === 'S1' && $mahasiswa->jalur_program === 'RPL') {
            $rules['ijazah_slta_asli'] = 'nullable|file|mimes:pdf|max:5120';
            $rules['transkrip_nilai'] = 'nullable|file|mimes:pdf|max:5120';
            $rules['ijazah_d3_d4_s1'] = 'nullable|file|mimes:pdf|max:5120';
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, [
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
                'formulir_pendaftaran', 'formulir_keabsahan', 'foto_formal', 'ktp', 'ijazah_slta'
            ];

            // Tambah field untuk RPL
            if ($mahasiswa->jenjang === 'S1' && $mahasiswa->jalur_program === 'RPL') {
                $fields = array_merge($fields, ['ijazah_slta_asli', 'transkrip_nilai', 'ijazah_d3_d4_s1']);
            }

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
