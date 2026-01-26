<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\DokumenMahasiswa;

echo "Memeriksa dan mengupdate status dokumen...\n\n";

$dokumenList = DokumenMahasiswa::where('status_dokumen', 'belum_lengkap')->get();

echo "Total dokumen dengan status 'belum_lengkap': " . $dokumenList->count() . "\n\n";

$updated = 0;

foreach ($dokumenList as $dokumen) {
    $mahasiswa = $dokumen->mahasiswa;
    
    if (!$mahasiswa) {
        echo "Mahasiswa tidak ditemukan untuk dokumen ID: {$dokumen->id}\n";
        continue;
    }
    
    echo "Memeriksa dokumen mahasiswa: {$mahasiswa->nama_lengkap} (ID: {$mahasiswa->id}, Jenjang: {$mahasiswa->jenjang}, Jalur: {$mahasiswa->jalur_program})\n";
    
    // Cek kelengkapan
    if ($dokumen->isDokumenLengkap()) {
        $dokumen->status_dokumen = 'lengkap';
        $dokumen->save();
        $updated++;
        echo "  ✓ Status diupdate ke 'lengkap'\n";
    } else {
        echo "  ✗ Masih belum lengkap\n";
        
        // Tampilkan dokumen yang belum diupload
        $jenjang = $mahasiswa->jenjang;
        $jalurProgram = $mahasiswa->jalur_program;
        
        if (in_array($jenjang, ['D3', 'D4', 'S1']) && $jalurProgram === 'Non RPL') {
            echo "    Dokumen yang belum diupload:\n";
            if (!$dokumen->formulir_pendaftaran) echo "      - Formulir Pendaftaran\n";
            if (!$dokumen->formulir_keabsahan) echo "      - Lembar Keabsahan Dokumen\n";
            if (!$dokumen->foto_formal) echo "      - Foto Formal\n";
            if (!$dokumen->ktp) echo "      - KTP\n";
            if (!$dokumen->ijazah_slta) echo "      - Ijazah SLTA\n";
        }
        elseif ($jenjang === 'S1' && $jalurProgram === 'RPL') {
            echo "    Dokumen yang belum diupload:\n";
            if (!$dokumen->formulir_pendaftaran) echo "      - Formulir Pendaftaran\n";
            if (!$dokumen->formulir_keabsahan) echo "      - Lembar Keabsahan Dokumen\n";
            if (!$dokumen->foto_formal) echo "      - Foto Formal\n";
            if (!$dokumen->ktp) echo "      - KTP\n";
            if (!$dokumen->ijazah_slta_asli) echo "      - Ijazah SLTA Asli\n";
            if (!$dokumen->transkrip_nilai) echo "      - Transkrip Nilai\n";
        }
        elseif ($jenjang === 'S2') {
            echo "    Dokumen yang belum diupload:\n";
            if (!$dokumen->formulir_pendaftaran) echo "      - Formulir Pendaftaran\n";
            if (!$dokumen->formulir_keabsahan) echo "      - Lembar Keabsahan Dokumen\n";
            if (!$dokumen->foto_formal) echo "      - Foto Formal\n";
            if (!$dokumen->ktp) echo "      - KTP\n";
            if (!$dokumen->ijazah_slta) echo "      - Ijazah SLTA\n";
            if (!$dokumen->sertifikat_akreditasi_prodi) echo "      - Sertifikat Akreditasi Prodi\n";
            if (!$dokumen->transkrip_d3_d4_s1) echo "      - FC Ijazah dan Transkrip Nilai D4/S1\n";
            if (!$dokumen->riwayat_hidup) echo "      - Riwayat Hidup\n";
            if (!$dokumen->sertifikat_toefl) echo "      - Sertifikat TOEFL\n";
            if (!$dokumen->rancangan_penelitian) echo "      - Rancangan Penelitian\n";
            if (!$dokumen->sk_mampu_komputer) echo "      - SK Mampu Komputer\n";
            if (!$dokumen->bukti_tes_tpa) echo "      - Bukti TPA\n";
            if (!$dokumen->seleksi_tes_substansi) echo "      - Seleksi Tes Substansi\n";
            if (!$dokumen->formulir_isian_foto) echo "      - Formulir Isian Foto\n";
        }
        elseif ($jenjang === 'S3') {
            echo "    Dokumen yang belum diupload:\n";
            if (!$dokumen->formulir_pendaftaran) echo "      - Formulir Pendaftaran\n";
            if (!$dokumen->formulir_keabsahan) echo "      - Lembar Keabsahan Dokumen\n";
            if (!$dokumen->foto_formal) echo "      - Foto Formal\n";
            if (!$dokumen->ktp) echo "      - KTP\n";
            if (!$dokumen->ijazah_slta) echo "      - Ijazah SLTA\n";
            if (!$dokumen->sertifikat_akreditasi_prodi) echo "      - Sertifikat Akreditasi Prodi S2\n";
            if (!$dokumen->transkrip_d3_d4_s1) echo "      - FC Ijazah dan Transkrip Nilai S2\n";
            if (!$dokumen->riwayat_hidup) echo "      - Riwayat Hidup\n";
            if (!$dokumen->sertifikat_toefl) echo "      - Sertifikat TOEFL\n";
            if (!$dokumen->rancangan_penelitian) echo "      - Rancangan Penelitian\n";
            if (!$dokumen->sk_mampu_komputer) echo "      - SK Mampu Komputer\n";
            if (!$dokumen->bukti_tes_tpa) echo "      - Bukti TPA\n";
            if (!$dokumen->seleksi_tes_substansi) echo "      - Seleksi Tes Substansi\n";
        }
    }
    
    echo "\n";
}

echo "========================================\n";
echo "Total dokumen yang diupdate: {$updated}\n";
echo "Selesai!\n";
