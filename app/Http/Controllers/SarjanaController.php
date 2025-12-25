<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SarjanaController extends Controller
{
    /**
     * Tampilkan form registrasi sarjana
     */
    public function show()
    {
        return view('pages.sarjana');
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
            'email'           => 'required|email|max:255|unique:users,email',
            'password'        => 'required|min:8|confirmed',
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
            'captcha_answer.required' => 'Jawaban captcha wajib diisi.',
            'captcha_answer.numeric' => 'Jawaban captcha harus berupa angka.',
        ];

        // Validasi kondisional untuk WNI
        if ($request->kewarganegaraan === 'WNI') {
            $rules['nik'] = [
                'required',
                'numeric',
                'digits:16',
                'unique:users,nik'
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
                'unique:users,passport'
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
           HASH PASSWORD
        =============================== */
        $validated['password'] = Hash::make($validated['password']);

        // ❗ Simpan ke database jika perlu
        // Sarjana::create($validated);

        return back()->with('success', 'Pendaftaran Sarjana berhasil! Data Anda telah tersimpan.');
    }
}
