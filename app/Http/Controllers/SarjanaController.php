<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SarjanaController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
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
            'agama'             => 'required|string',
            'alamat'            => 'required|string',
            'kewarganegaraan'   => 'required|in:WNI,WNA',
            'nik'               => 'required|digits:16',
            'jalur_program'     => 'required|string',
            'jenjang'           => 'required|string',
            'program_studi'     => 'required|string',
            'email'             => 'required|email',
            'password'          => 'required|min:8|confirmed',
            'captcha_answer'    => 'required|numeric',
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Simpan ke DB jika diperlukan
        // Sarjana::create($validated);

        return back()->with('success', 'Pendaftaran Sarjana berhasil');
    }
}
