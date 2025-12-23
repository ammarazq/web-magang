<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class UmurController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate(
            [
                'tanggal_lahir' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) {
                        $umur = Carbon::parse($value)->age;

                        if ($umur < 15) {
                            $fail('Tahun lahir selisih minimal 15 tahun dari tahun berjalan.');
                        }
                    }
                ],
            ],
            [
                'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
                'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            ]
        );

        return back()->with('success', 'Validasi umur berhasil.');
    }
}
