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
        // Dokumen Non RPL
        'ijazah_slta',
        'foto_formal',
        'ktp',
        'formulir_keabsahan',
        'formulir_pendaftaran',
        // Dokumen RPL
        'ijazah_pendidikan_terakhir',
        'transkrip_nilai',
        'ijazah_slta_asli',
        'riwayat_hidup',
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
     * Check apakah dokumen sudah lengkap berdasarkan jalur program
     */
    public function isDokumenLengkap()
    {
        $mahasiswa = $this->mahasiswa;
        
        if (!$mahasiswa) {
            return false;
        }

        if ($mahasiswa->jalur_program === 'Non RPL') {
            // Cek dokumen Non RPL
            return !empty($this->ijazah_slta) &&
                   !empty($this->foto_formal) &&
                   !empty($this->ktp) &&
                   !empty($this->formulir_keabsahan) &&
                   !empty($this->formulir_pendaftaran);
        } else { // RPL
            // Cek dokumen RPL
            return !empty($this->ijazah_pendidikan_terakhir) &&
                   !empty($this->transkrip_nilai) &&
                   !empty($this->ijazah_slta_asli) &&
                   !empty($this->foto_formal) &&
                   !empty($this->ktp) &&
                   !empty($this->formulir_keabsahan) &&
                   !empty($this->formulir_pendaftaran) &&
                   !empty($this->riwayat_hidup);
        }
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

        if ($mahasiswa->jalur_program === 'Non RPL') {
            $required = 5; // jumlah dokumen wajib
            $uploaded = 0;
            
            if ($this->ijazah_slta) $uploaded++;
            if ($this->foto_formal) $uploaded++;
            if ($this->ktp) $uploaded++;
            if ($this->formulir_keabsahan) $uploaded++;
            if ($this->formulir_pendaftaran) $uploaded++;
            
            return round(($uploaded / $required) * 100);
        } else { // RPL
            $required = 8; // jumlah dokumen wajib
            $uploaded = 0;
            
            if ($this->ijazah_pendidikan_terakhir) $uploaded++;
            if ($this->transkrip_nilai) $uploaded++;
            if ($this->ijazah_slta_asli) $uploaded++;
            if ($this->foto_formal) $uploaded++;
            if ($this->ktp) $uploaded++;
            if ($this->formulir_keabsahan) $uploaded++;
            if ($this->formulir_pendaftaran) $uploaded++;
            if ($this->riwayat_hidup) $uploaded++;
            
            return round(($uploaded / $required) * 100);
        }
    }
}
