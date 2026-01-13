<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Tampilkan form registrasi
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi pengguna baru
     */
    public function doRegister(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar, gunakan email lain',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Buat user baru dengan password yang di-hash otomatis
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hash password
            ]);

            // Login otomatis setelah registrasi
            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Tampilkan form login
     */
    public function login()
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses login pengguna
     */
    public function doLogin(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Ambil credentials dari request
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // Coba login
        if (Auth::attempt($credentials, $remember)) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Login berhasil! Selamat datang kembali, ' . Auth::user()->name);
        }

        // Jika login gagal
        return redirect()->back()
            ->with('error', 'Email atau password salah')
            ->withInput($request->only('email'));
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Logout berhasil');
    }

    /**
     * Tampilkan dashboard setelah login
     */
    public function dashboard()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        
        // Jika user adalah mahasiswa, ambil atau buat data dokumen
        if ($mahasiswa) {
            $dokumen = $mahasiswa->dokumen;
            
            // Jika belum ada data dokumen, buat baru
            if (!$dokumen) {
                $dokumen = \App\Models\DokumenMahasiswa::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'status_dokumen' => 'belum_lengkap'
                ]);
            }
            
            return view('auth.dashboard', compact('user', 'mahasiswa', 'dokumen'));
        }
        
        return view('auth.dashboard', compact('user'));
    }

    /**
     * Upload dokumen mahasiswa
     */
    public function uploadDokumen(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan');
        }

        $dokumen = $mahasiswa->dokumen;
        
        if (!$dokumen) {
            $dokumen = \App\Models\DokumenMahasiswa::create([
                'mahasiswa_id' => $mahasiswa->id,
                'status_dokumen' => 'belum_lengkap'
            ]);
        }

        // Validasi file berdasarkan jalur program
        $rules = [];
        
        if ($mahasiswa->jalur_program === 'Non RPL') {
            $rules = [
                'ijazah_slta' => 'nullable|file|mimes:pdf|max:2048',
                'foto_formal' => 'nullable|file|mimes:jpg,jpeg|max:2048',
                'ktp' => 'nullable|file|mimes:jpg,jpeg|max:2048',
                'formulir_keabsahan' => 'nullable|file|mimes:jpg,jpeg|max:2048',
                'formulir_pendaftaran' => 'nullable|file|mimes:jpg,jpeg|max:2048',
            ];
        } else { // RPL
            $rules = [
                'ijazah_pendidikan_terakhir' => 'nullable|file|mimes:pdf|max:2048',
                'transkrip_nilai' => 'nullable|file|mimes:pdf|max:2048',
                'ijazah_slta_asli' => 'nullable|file|mimes:pdf|max:2048',
                'foto_formal' => 'nullable|file|mimes:jpg,jpeg|max:2048',
                'ktp' => 'nullable|file|mimes:jpg,jpeg|max:2048',
                'formulir_keabsahan' => 'nullable|file|mimes:jpg,jpeg|max:2048',
                'formulir_pendaftaran' => 'nullable|file|mimes:jpg,jpeg|max:2048',
                'riwayat_hidup' => 'nullable|file|mimes:pdf|max:2048',
            ];
        }

        $validated = $request->validate($rules);

        // Upload dan simpan file
        foreach ($validated as $field => $file) {
            if ($request->hasFile($field)) {
                // Hapus file lama jika ada
                if ($dokumen->$field && \Storage::exists('public/dokumen/' . $dokumen->$field)) {
                    \Storage::delete('public/dokumen/' . $dokumen->$field);
                }
                
                // Simpan file baru
                $filename = $mahasiswa->id . '_' . $field . '_' . time() . '.' . $file->extension();
                $path = $file->storeAs('public/dokumen', $filename);
                $dokumen->$field = $filename;
            }
        }

        // Update status dokumen
        if ($dokumen->isDokumenLengkap()) {
            $dokumen->status_dokumen = 'lengkap';
        }

        $dokumen->save();

        return redirect()->route('dashboard')->with('success', 'Dokumen berhasil diupload!');
    }
}
