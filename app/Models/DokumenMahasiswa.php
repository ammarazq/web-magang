<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'dokumen_mahasiswa';

    protected $fillable = [
        'mahasiswa_id',
        // Dokumen Umum
        'formulir_pendaftaran',
        'formulir_keabsahan',
        'foto_formal',
        'ktp',
        'ijazah_slta',
        // Dokumen S1 RPL
        'ijazah_slta_asli',
        'transkrip_nilai',
        'ijazah_d3_d4_s1',
        // Dokumen S2
        'sertifikat_akreditasi_prodi',
        'transkrip_d3_d4_s1',
        'sertifikat_toefl',
        'rancangan_penelitian',
        'sk_mampu_komputer',
        'bukti_tes_tpa',
        'seleksi_tes_substansi',
        'formulir_isian_foto',
        'riwayat_hidup',
        // Dokumen S3
        'ijazah_s2',
        'transkrip_s2',
        'sertifikat_akreditasi_s2',
        // Berkas Dokumen Pendaftaran (untuk S2 dan S3)
        'berkas_dokumen_pendaftaran',
        // Status
        'status_dokumen',
        'catatan_verifikasi',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    /**
     * Relasi ke mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    /**
     * Relasi ke admin yang memverifikasi
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Check apakah dokumen sudah lengkap berdasarkan jalur program dan jenjang
     */
    public function isDokumenLengkap()
    {
        $mahasiswa = $this->mahasiswa;
        
        if (!$mahasiswa) {
            return false;
        }

        $jenjang = $mahasiswa->jenjang;
        $jalurProgram = $mahasiswa->jalur_program;
        
        // D3/D4 REGULER (Non RPL atau RPL - keduanya sama saja untuk D3/D4)
        if (in_array($jenjang, ['D3', 'D4'])) {
            return !empty($this->formulir_pendaftaran) &&
                   !empty($this->formulir_keabsahan) &&
                   !empty($this->foto_formal) &&
                   !empty($this->ktp) &&
                   !empty($this->ijazah_slta);
        }
        
        // S1 REGULER (Non RPL)
        if ($jenjang === 'S1' && $jalurProgram === 'Non RPL') {
            return !empty($this->formulir_pendaftaran) &&
                   !empty($this->formulir_keabsahan) &&
                   !empty($this->foto_formal) &&
                   !empty($this->ktp) &&
                   !empty($this->ijazah_slta);
        }
        
        // S1 RPL
        if ($jenjang === 'S1' && $jalurProgram === 'RPL') {
            return !empty($this->formulir_pendaftaran) &&
                   !empty($this->formulir_keabsahan) &&
                   !empty($this->foto_formal) &&
                   !empty($this->ktp) &&
                   !empty($this->ijazah_slta_asli) &&
                   !empty($this->transkrip_nilai);
                   // ijazah_d3_d4_s1 optional (jika sudah lulus)
        }
        
        // S2
        if ($jenjang === 'S2') {
            return !empty($this->formulir_pendaftaran) &&
                   !empty($this->formulir_keabsahan) &&
                   !empty($this->foto_formal) &&
                   !empty($this->ktp) &&
                   !empty($this->ijazah_slta) &&
                   !empty($this->sertifikat_akreditasi_prodi) &&
                   !empty($this->transkrip_d3_d4_s1) &&
                   !empty($this->riwayat_hidup) &&
                   !empty($this->sertifikat_toefl) &&
                   !empty($this->rancangan_penelitian) &&
                   !empty($this->sk_mampu_komputer) &&
                   !empty($this->bukti_tes_tpa) &&
                   !empty($this->seleksi_tes_substansi) &&
                   !empty($this->formulir_isian_foto) &&
                   !empty($this->berkas_dokumen_pendaftaran);
        }
        
        // S3
        if ($jenjang === 'S3') {
            return !empty($this->formulir_pendaftaran) &&
                   !empty($this->formulir_keabsahan) &&
                   !empty($this->foto_formal) &&
                   !empty($this->ktp) &&
                   !empty($this->ijazah_slta) &&
                   !empty($this->sertifikat_akreditasi_prodi) &&
                   !empty($this->transkrip_d3_d4_s1) &&
                   !empty($this->riwayat_hidup) &&
                   !empty($this->sertifikat_toefl) &&
                   !empty($this->rancangan_penelitian) &&
                   !empty($this->sk_mampu_komputer) &&
                   !empty($this->bukti_tes_tpa) &&
                   !empty($this->seleksi_tes_substansi) &&
                   !empty($this->berkas_dokumen_pendaftaran);
        }
        
        return false;
    }

    /**
     * Get persentase kelengkapan dokumen
     */
    public function getPersentaseKelengkapan()
    {
        $mahasiswa = $this->mahasiswa;
        
        if (!$mahasiswa) {
            return 0;
        }

        $jenjang = $mahasiswa->jenjang;
        $jalurProgram = $mahasiswa->jalur_program;
        $required = 0;
        $uploaded = 0;
        
        // D3/D4 (Non RPL atau RPL - keduanya sama saja)
        if (in_array($jenjang, ['D3', 'D4'])) {
            $required = 5;
            if ($this->formulir_pendaftaran) $uploaded++;
            if ($this->formulir_keabsahan) $uploaded++;
            if ($this->foto_formal) $uploaded++;
            if ($this->ktp) $uploaded++;
            if ($this->ijazah_slta) $uploaded++;
        }
        // S1 Non RPL
        elseif ($jenjang === 'S1' && $jalurProgram === 'Non RPL') {
            $required = 5;
            if ($this->formulir_pendaftaran) $uploaded++;
            if ($this->formulir_keabsahan) $uploaded++;
            if ($this->foto_formal) $uploaded++;
            if ($this->ktp) $uploaded++;
            if ($this->ijazah_slta) $uploaded++;
        }
        // S1 RPL
        elseif ($jenjang === 'S1' && $jalurProgram === 'RPL') {
            $required = 6; // 6 wajib, ijazah_d3_d4_s1 opsional (tidak dihitung)
            if ($this->formulir_pendaftaran) $uploaded++;
            if ($this->formulir_keabsahan) $uploaded++;
            if ($this->foto_formal) $uploaded++;
            if ($this->ktp) $uploaded++;
            if ($this->ijazah_slta_asli) $uploaded++;
            if ($this->transkrip_nilai) $uploaded++;
            // ijazah_d3_d4_s1 opsional, TIDAK dihitung agar max 100%
        }
        // S2
        elseif ($jenjang === 'S2') {
            $required = 10; // Hanya dokumen wajib
            if ($this->formulir_pendaftaran) $uploaded++;
            if ($this->formulir_keabsahan) $uploaded++;
            if ($this->foto_formal) $uploaded++;
            if ($this->ktp) $uploaded++;
            if ($this->ijazah_slta) $uploaded++;
            if ($this->sertifikat_akreditasi_prodi) $uploaded++;
            if ($this->transkrip_d3_d4_s1) $uploaded++;
            if ($this->sertifikat_toefl) $uploaded++;
            if ($this->rancangan_penelitian) $uploaded++;
            if ($this->berkas_dokumen_pendaftaran) $uploaded++;
            // Dokumen opsional TIDAK dihitung:
            // - riwayat_hidup
            // - sk_mampu_komputer
            // - bukti_tes_tpa
            // - seleksi_tes_substansi
            // - formulir_isian_foto
        }
        // S3
        elseif ($jenjang === 'S3') {
            $required = 11; // Hanya dokumen wajib sesuai UPLOAD_DOKUMEN_GUIDE.md
            if ($this->formulir_pendaftaran) $uploaded++;
            if ($this->formulir_keabsahan) $uploaded++;
            if ($this->foto_formal) $uploaded++;
            if ($this->ktp) $uploaded++;
            if ($this->ijazah_slta) $uploaded++;
            if ($this->ijazah_s2) $uploaded++;
            if ($this->transkrip_s2) $uploaded++;
            if ($this->sertifikat_akreditasi_s2) $uploaded++;
            if ($this->sertifikat_toefl) $uploaded++;
            if ($this->rancangan_penelitian) $uploaded++; // Proposal Penelitian/Disertasi
            if ($this->berkas_dokumen_pendaftaran) $uploaded++;
            // Dokumen opsional TIDAK dihitung:
            // - sertifikat_akreditasi_s1
            // - transkrip_d3_d4_s1 (transkrip S1)
            // - riwayat_hidup
            // - sk_mampu_komputer
            // - bukti_tes_tpa
            // - seleksi_tes_substansi
            // - formulir_isian_foto
        }
        
        return $required > 0 ? round(($uploaded / $required) * 100) : 0;
    }

    /**
     * Get jumlah dokumen yang sudah di-upload vs total dokumen wajib
     */
    public function getJumlahDokumen()
    {
        $mahasiswa = $this->mahasiswa;
        
        if (!$mahasiswa) {
            return ['uploaded' => 0, 'total' => 0];
        }

        $jenjang = $mahasiswa->jenjang;
        $jalurProgram = $mahasiswa->jalur_program;
        $required = 0;
        $uploaded = 0;
        
        // D3/D4 (Non RPL atau RPL - keduanya sama saja)
        if (in_array($jenjang, ['D3', 'D4'])) {
            $required = 5;
            if ($this->formulir_pendaftaran) $uploaded++;
            if ($this->formulir_keabsahan) $uploaded++;
            if ($this->foto_formal) $uploaded++;
            if ($this->ktp) $uploaded++;
            if ($this->ijazah_slta) $uploaded++;
        }
        // S1 Non RPL
        elseif ($jenjang === 'S1' && $jalurProgram === 'Non RPL') {
            $required = 5;
            if ($this->formulir_pendaftaran) $uploaded++;
            if ($this->formulir_keabsahan) $uploaded++;
            if ($this->foto_formal) $uploaded++;
            if ($this->ktp) $uploaded++;
            if ($this->ijazah_slta) $uploaded++;
        }
        // S1 RPL
        elseif ($jenjang === 'S1' && $jalurProgram === 'RPL') {
            $required = 6;
            if ($this->formulir_pendaftaran) $uploaded++;
            if ($this->formulir_keabsahan) $uploaded++;
            if ($this->foto_formal) $uploaded++;
            if ($this->ktp) $uploaded++;
            if ($this->ijazah_slta_asli) $uploaded++;
            if ($this->transkrip_nilai) $uploaded++;
        }
        // S2
        elseif ($jenjang === 'S2') {
            $required = 10;
            if ($this->formulir_pendaftaran) $uploaded++;
            if ($this->formulir_keabsahan) $uploaded++;
            if ($this->foto_formal) $uploaded++;
            if ($this->ktp) $uploaded++;
            if ($this->ijazah_slta) $uploaded++;
            if ($this->sertifikat_akreditasi_prodi) $uploaded++;
            if ($this->transkrip_d3_d4_s1) $uploaded++;
            if ($this->sertifikat_toefl) $uploaded++;
            if ($this->rancangan_penelitian) $uploaded++;
            if ($this->berkas_dokumen_pendaftaran) $uploaded++;
        }
        // S3
        elseif ($jenjang === 'S3') {
            $required = 11;
            if ($this->formulir_pendaftaran) $uploaded++;
            if ($this->formulir_keabsahan) $uploaded++;
            if ($this->foto_formal) $uploaded++;
            if ($this->ktp) $uploaded++;
            if ($this->ijazah_slta) $uploaded++;
            if ($this->ijazah_s2) $uploaded++;
            if ($this->transkrip_s2) $uploaded++;
            if ($this->sertifikat_akreditasi_s2) $uploaded++;
            if ($this->sertifikat_toefl) $uploaded++;
            if ($this->rancangan_penelitian) $uploaded++;
            if ($this->berkas_dokumen_pendaftaran) $uploaded++;
        }
        
        return [
            'uploaded' => $uploaded,
            'total' => $required
        ];
    }
}
