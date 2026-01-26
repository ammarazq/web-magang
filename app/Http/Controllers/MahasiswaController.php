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

        // Tentukan dokumen yang diperlukan berdasarkan jenjang dan jalur program
        $requiredDocs = $this->getRequiredDocuments($mahasiswa->jenjang, $mahasiswa->jalur_program);
        
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
            // Dokumen Umum
            'formulir_pendaftaran' => 'Formulir Pendaftaran',
            'formulir_keabsahan' => 'Lembar Keabsahan Dokumen',
            'foto_formal' => 'Foto Formal',
            'ktp' => 'KTP',
            'ijazah_slta' => 'FC Ijazah SLTA Legalisir',
            // Dokumen S1 RPL
            'ijazah_slta_asli' => 'Ijazah SLTA Asli',
            'transkrip_nilai' => 'Transkrip Nilai D3/D4/S1',
            'ijazah_d3_d4_s1' => 'FC Ijazah D3/D4/S1 Legalisir',
            // Dokumen S2/S3
            'sertifikat_akreditasi_prodi' => 'Sertifikat Akreditasi Prodi D4/S1',
            'transkrip_d3_d4_s1' => 'FC Transkrip Nilai D4/S1 Legalisir',
            'sertifikat_toefl' => 'Sertifikat TOEFL',
            'rancangan_penelitian' => 'Rancangan Penelitian Singkat',
            'sk_mampu_komputer' => 'SK Mampu menggunakan Komputer',
            'bukti_tes_tpa' => 'Bukti Tes Potensi Akademik (TPA)',
            'seleksi_tes_substansi' => 'Seleksi Tes Substansi',
            'formulir_isian_foto' => 'Formulir Isian Foto',
            'riwayat_hidup' => 'Daftar Riwayat Hidup',
            'ijazah_s2' => 'FC Ijazah S2 Legalisir',
            'transkrip_s2' => 'FC Transkrip Nilai S2 Legalisir',
            'sertifikat_akreditasi_s2' => 'Sertifikat Akreditasi Prodi S2',
        ];

        foreach ($fieldNames as $field => $name) {
            if ($request->hasFile($field)) {
                // Tentukan tipe file yang diizinkan
                if (in_array($field, ['foto_formal', 'ktp', 'formulir_keabsahan', 'formulir_pendaftaran', 'formulir_isian_foto'])) {
                    $rules[$field] = 'required|mimes:jpg,jpeg,png,pdf|max:2048';
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
     * Get required documents based on jenjang and jalur program
     */
    private function getRequiredDocuments($jenjang, $jalurProgram)
    {
        $docs = [];
        
        // D3/D4/S1 REGULER
        if (in_array($jenjang, ['D3', 'D4', 'S1']) && $jalurProgram === 'Non RPL') {
            $docs = [
                'formulir_pendaftaran' => 'Formulir Pendaftaran',
                'formulir_keabsahan' => 'Lembar Keabsahan Dokumen',
                'foto_formal' => 'Scan Foto Formal',
                'ktp' => 'Scan KTP Asli',
                'ijazah_slta' => 'FC Ijazah SLTA Legalisir',
            ];
        }
        // S1 RPL
        elseif ($jenjang === 'S1' && $jalurProgram === 'RPL') {
            $docs = [
                'formulir_pendaftaran' => 'Formulir Pendaftaran',
                'formulir_keabsahan' => 'Lembar Keabsahan Dokumen',
                'ktp' => 'Scan KTP Asli',
                'ijazah_slta_asli' => 'Ijazah SLTA Asli',
                'transkrip_nilai' => 'Transkrip Nilai D3/D4/S1',
                'ijazah_d3_d4_s1' => 'FC Ijazah D3/D4/S1 Legalisir (Jika sudah lulus)',
            ];
        }
        // S2
        elseif ($jenjang === 'S2') {
            $docs = [
                'formulir_pendaftaran' => 'Formulir Pendaftaran',
                'formulir_keabsahan' => 'Lembar Keabsahan Dokumen',
                'foto_formal' => 'Scan Foto Formal',
                'ktp' => 'Scan KTP Asli',
                'ijazah_slta' => 'FC Ijazah SLTA Legalisir',
                'sertifikat_akreditasi_prodi' => 'Sertifikat Akreditasi Prodi D4/S1',
                'ijazah_d3_d4_s1' => 'FC Ijazah D4/S1 Legalisir',
                'transkrip_d3_d4_s1' => 'FC Transkrip Nilai D4/S1 Legalisir',
                'riwayat_hidup' => 'Daftar Riwayat Hidup',
                'sertifikat_toefl' => 'Sertifikat TOEFL min. 450 (maksimal 2 tahun terakhir)',
                'rancangan_penelitian' => 'Rancangan Penelitian Singkat',
                'sk_mampu_komputer' => 'SK Mampu menggunakan Komputer',
                'bukti_tes_tpa' => 'Bukti telah mengikuti Tes Potensi Akademik (TPA)',
                'seleksi_tes_substansi' => 'Mengikuti Seleksi Tes Substansi',
                'formulir_isian_foto' => 'Formulir Isian Foto',
            ];
        }
        // S3
        elseif ($jenjang === 'S3') {
            $docs = [
                'formulir_pendaftaran' => 'Formulir Pendaftaran',
                'formulir_keabsahan' => 'Lembar Keabsahan Dokumen',
                'foto_formal' => 'Scan Foto Formal',
                'ktp' => 'Scan KTP Asli',
                'ijazah_slta' => 'FC Ijazah SLTA Legalisir',
                'sertifikat_akreditasi_s2' => 'Sertifikat Akreditasi Prodi S2',
                'ijazah_s2' => 'FC Ijazah S2 Legalisir',
                'transkrip_s2' => 'FC Transkrip Nilai S2 Legalisir',
                'riwayat_hidup' => 'Daftar Riwayat Hidup',
                'sertifikat_toefl' => 'Sertifikat TOEFL min. 500 (maksimal 2 tahun terakhir)',
                'rancangan_penelitian' => 'Rancangan Penelitian Singkat',
                'sk_mampu_komputer' => 'SK Mampu menggunakan Komputer',
                'bukti_tes_tpa' => 'Bukti telah mengikuti Tes Potensi Akademik (TPA)',
                'seleksi_tes_substansi' => 'Mengikuti Seleksi Tes Substansi',
                'formulir_isian_foto' => 'Formulir Isian Foto',
            ];
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
            'formulir_pendaftaran' => 'Formulir Pendaftaran',
            'formulir_keabsahan' => 'Lembar Keabsahan Dokumen',
            'foto_formal' => 'Foto Formal',
            'ktp' => 'KTP',
            'ijazah_slta' => 'FC Ijazah SLTA Legalisir',
            'ijazah_slta_asli' => 'Ijazah SLTA Asli',
            'transkrip_nilai' => 'Transkrip Nilai',
            'ijazah_d3_d4_s1' => 'FC Ijazah D3/D4/S1 Legalisir',
            'sertifikat_akreditasi_prodi' => 'Sertifikat Akreditasi Prodi',
            'transkrip_d3_d4_s1' => 'FC Transkrip Nilai D4/S1 Legalisir',
            'sertifikat_toefl' => 'Sertifikat TOEFL',
            'rancangan_penelitian' => 'Rancangan Penelitian Singkat',
            'sk_mampu_komputer' => 'SK Mampu menggunakan Komputer',
            'bukti_tes_tpa' => 'Bukti Tes TPA',
            'seleksi_tes_substansi' => 'Seleksi Tes Substansi',
            'formulir_isian_foto' => 'Formulir Isian Foto',
            'riwayat_hidup' => 'Daftar Riwayat Hidup',
            'ijazah_s2' => 'FC Ijazah S2 Legalisir',
            'transkrip_s2' => 'FC Transkrip Nilai S2 Legalisir',
            'sertifikat_akreditasi_s2' => 'Sertifikat Akreditasi Prodi S2',
        ];

        foreach ($fields as $field => $name) {
            if (!empty($dokumen->$field)) {
                $uploaded[$field] = $name;
            }
        }

        return $uploaded;
    }
}
