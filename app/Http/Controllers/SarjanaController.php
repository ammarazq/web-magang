<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SarjanaController extends Controller
{
    public function submit(Request $request)
    {
        // Validasi dasar
        $rules = [
            'nama_lengkap'      => 'required|string|max:255',
            'tempat_lahir'      => 'required|string|max:255',
            'tanggal_lahir'     => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $tanggalLahir = Carbon::parse($value);
                    $umur = $tanggalLahir->age;

                    if ($umur < 15) {
                        $fail('Usia pendaftar minimal 15 tahun dari tahun berjalan.');
                    }
                }
            ],
            'jenis_kelamin'     => 'required|in:L,P',
            'nama_ibu'          => 'required|string|max:255',
            'agama'             => 'required|string|in:Islam,Protestan,Katolik,Hindu,Budha,Konghucu',
            'alamat'            => 'required|string',
            'kewarganegaraan'   => 'required|in:WNI,WNA',
            'jalur_program'     => 'required|string|in:RPL,Non RPL',
            'jenjang'           => 'required|string|in:D3,D4,S1',
            'program_studi'     => 'required|string',
            'no_hp'             => [
                'required',
                'numeric',
                'digits_between:10,15'
            ],
            'email'             => 'required|email|max:255',
            'password'          => 'required|min:8|confirmed',
            'captcha_answer'    => 'required|numeric',
        ];

        // Validasi NIK untuk WNI
        if ($request->kewarganegaraan === 'WNI') {
            $rules['nik'] = [
                'required',
                'numeric',
                'digits:16'
            ];
        }

        // Validasi Negara dan Passport untuk WNA
        if ($request->kewarganegaraan === 'WNA') {
            $rules['negara'] = 'required|string|max:255';
            $rules['passport'] = [
                'required',
                'string',
                'max:15',
                'min:6'
            ];
        }

        // Pesan error kustom
        $messages = [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'nama_lengkap.max' => 'Nama lengkap maksimal 255 karakter',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
            'nama_ibu.required' => 'Nama ibu kandung wajib diisi',
            'agama.required' => 'Agama wajib dipilih',
            'agama.in' => 'Agama yang dipilih tidak valid',
            'alamat.required' => 'Alamat wajib diisi',
            'kewarganegaraan.required' => 'Kewarganegaraan wajib dipilih',
            'kewarganegaraan.in' => 'Kewarganegaraan tidak valid',
            'nik.required' => 'NIK wajib diisi untuk WNI',
            'nik.numeric' => 'NIK harus berupa angka',
            'nik.digits' => 'NIK harus terdiri dari 16 digit',
            'negara.required' => 'Negara wajib dipilih untuk WNA',
            'passport.required' => 'Nomor passport wajib diisi untuk WNA',
            'passport.max' => 'Nomor passport maksimal 15 karakter',
            'passport.min' => 'Nomor passport minimal 6 karakter',
            'jalur_program.required' => 'Jalur program wajib dipilih',
            'jalur_program.in' => 'Jalur program tidak valid',
            'jenjang.required' => 'Jenjang pendidikan wajib dipilih',
            'jenjang.in' => 'Jenjang pendidikan tidak valid',
            'program_studi.required' => 'Program studi wajib dipilih',
            'no_hp.required' => 'Nomor HP wajib diisi',
            'no_hp.numeric' => 'Nomor HP harus berupa angka',
            'no_hp.digits_between' => 'Nomor HP harus antara 10-15 digit',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'captcha_answer.required' => 'Captcha wajib diisi',
            'captcha_answer.numeric' => 'Captcha harus berupa angka',
        ];

        $validated = $request->validate($rules, $messages);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Simpan ke DB jika diperlukan
        // Sarjana::create($validated);

        return back()->with('success', 'Pendaftaran Sarjana berhasil');
    }
}
