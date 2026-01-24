<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\DokumenMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    /**
     * Dashboard mahasiswa - menampilkan status dokumen
     */
    public function dashboard()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('login')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Cek apakah sudah ada dokumen
        $dokumen = DokumenMahasiswa::where('mahasiswa_id', $mahasiswa->id)->first();

        // Jika belum ada record dokumen, buat baru
        if (!$dokumen) {
            $dokumen = DokumenMahasiswa::create([
                'mahasiswa_id' => $mahasiswa->id,
                'status_dokumen' => 'belum_lengkap'
            ]);
        }

        // Tentukan dokumen yang diperlukan berdasarkan jalur program
        $requiredDocs = $this->getRequiredDocuments($mahasiswa->jalur_program);
        
        // Cek dokumen yang sudah diupload
        $uploadedDocs = $this->getUploadedDocuments($dokumen);
        
        // Dokumen yang belum diupload
        $missingDocs = array_diff($requiredDocs, $uploadedDocs);

        return view('mahasiswa.dashboard', compact('mahasiswa', 'dokumen', 'requiredDocs', 'uploadedDocs', 'missingDocs'));
    }

    /**
     * Upload dokumen
     */
    public function uploadDokumen(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $dokumen = DokumenMahasiswa::firstOrCreate(
            ['mahasiswa_id' => $mahasiswa->id],
            ['status_dokumen' => 'belum_lengkap']
        );

        // Validasi berdasarkan field yang diupload
        $rules = [];
        $fieldNames = [
            'ijazah_slta' => 'Ijazah SLTA',
            'foto_formal' => 'Foto Formal',
            'ktp' => 'KTP',
            'formulir_keabsahan' => 'Formulir Keabsahan',
            'formulir_pendaftaran' => 'Formulir Pendaftaran',
            'ijazah_pendidikan_terakhir' => 'Ijazah Pendidikan Terakhir',
            'transkrip_nilai' => 'Transkrip Nilai',
            'ijazah_slta_asli' => 'Ijazah SLTA Asli',
            'riwayat_hidup' => 'Riwayat Hidup',
        ];

        foreach ($fieldNames as $field => $name) {
            if ($request->hasFile($field)) {
                // Tentukan tipe file yang diizinkan
                if (in_array($field, ['foto_formal', 'ktp', 'formulir_keabsahan', 'formulir_pendaftaran'])) {
                    $rules[$field] = 'required|mimes:jpg,jpeg,png|max:2048';
                } else {
                    $rules[$field] = 'required|mimes:pdf|max:5120';
                }
            }
        }

        $request->validate($rules);

        // Upload files
        foreach ($fieldNames as $field => $name) {
            if ($request->hasFile($field)) {
                // Hapus file lama jika ada
                if ($dokumen->$field) {
                    Storage::disk('public')->delete($dokumen->$field);
                }

                // Upload file baru
                $file = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('dokumen_mahasiswa', $filename, 'public');
                
                $dokumen->$field = $path;
            }
        }

        // Cek apakah dokumen sudah lengkap
        if ($dokumen->isDokumenLengkap()) {
            $dokumen->status_dokumen = 'lengkap';
        }

        $dokumen->save();

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Dokumen berhasil diupload!');
    }

    /**
     * Get required documents based on jalur program
     */
    private function getRequiredDocuments($jalurProgram)
    {
        $docs = [
            'ijazah_slta' => 'Ijazah SLTA',
            'foto_formal' => 'Foto Formal',
            'ktp' => 'KTP',
            'formulir_keabsahan' => 'Formulir Keabsahan',
            'formulir_pendaftaran' => 'Formulir Pendaftaran',
        ];

        if ($jalurProgram === 'RPL') {
            $docs['ijazah_pendidikan_terakhir'] = 'Ijazah Pendidikan Terakhir';
            $docs['transkrip_nilai'] = 'Transkrip Nilai';
            $docs['ijazah_slta_asli'] = 'Ijazah SLTA Asli';
            $docs['riwayat_hidup'] = 'Riwayat Hidup';
        }

        return $docs;
    }

    /**
     * Get uploaded documents
     */
    private function getUploadedDocuments($dokumen)
    {
        $uploaded = [];
        $fields = [
            'ijazah_slta' => 'Ijazah SLTA',
            'foto_formal' => 'Foto Formal',
            'ktp' => 'KTP',
            'formulir_keabsahan' => 'Formulir Keabsahan',
            'formulir_pendaftaran' => 'Formulir Pendaftaran',
            'ijazah_pendidikan_terakhir' => 'Ijazah Pendidikan Terakhir',
            'transkrip_nilai' => 'Transkrip Nilai',
            'ijazah_slta_asli' => 'Ijazah SLTA Asli',
            'riwayat_hidup' => 'Riwayat Hidup',
        ];

        foreach ($fields as $field => $name) {
            if (!empty($dokumen->$field)) {
                $uploaded[$field] = $name;
            }
        }

        return $uploaded;
    }
}
