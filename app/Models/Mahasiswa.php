<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mahasiswa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Relasi
        'user_id',          // relasi ke tabel users
        
        // Data Pribadi
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'nama_ibu',
        'status_kawin',
        
        // Data Kewarganegaraan
        'kewarganegaraan',
        'nik',              // untuk WNI
        'negara',           // untuk WNA
        'passport',         // untuk WNA
        
        // Data Kontak
        'alamat',
        'no_hp',
        'email',
        'password',
        
        // Data Akademik (khusus Sarjana)
        'jalur_program',    // RPL / Non RPL
        'jenjang',          // D3, D4, S1
        'program_studi',
        
        // Jenis Pendaftaran
        'jenis_pendaftaran', // sarjana, magister, doktoral
        
        // Status
        'status_verifikasi', // pending, verified, rejected
        'catatan_verifikasi',
        
        // Metadata
        'email_verified_at',
        'verified_by',      // admin yang memverifikasi
        'verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'email_verified_at' => 'datetime',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $attributes = [
        'status_verifikasi' => 'pending',
    ];

    /**
     * Scope query untuk filter berdasarkan jenis pendaftaran
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $jenis
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeJenisPendaftaran($query, $jenis)
    {
        return $query->where('jenis_pendaftaran', $jenis);
    }

    /**
     * Scope query untuk filter berdasarkan status verifikasi
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatusVerifikasi($query, $status)
    {
        return $query->where('status_verifikasi', $status);
    }

    /**
     * Scope query untuk mahasiswa yang sudah diverifikasi
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified($query)
    {
        return $query->where('status_verifikasi', 'verified');
    }

    /**
     * Scope query untuk mahasiswa yang pending
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status_verifikasi', 'pending');
    }

    /**
     * Get mahasiswa sarjana
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSarjana($query)
    {
        return $query->where('jenis_pendaftaran', 'sarjana');
    }

    /**
     * Get mahasiswa magister
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMagister($query)
    {
        return $query->where('jenis_pendaftaran', 'magister');
    }

    /**
     * Get mahasiswa doktoral
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDoktoral($query)
    {
        return $query->where('jenis_pendaftaran', 'doktoral');
    }

    /**
     * Check if mahasiswa is WNI
     *
     * @return bool
     */
    public function isWNI()
    {
        return $this->kewarganegaraan === 'WNI';
    }

    /**
     * Check if mahasiswa is WNA
     *
     * @return bool
     */
    public function isWNA()
    {
        return $this->kewarganegaraan === 'WNA';
    }

    /**
     * Get nama program studi lengkap
     *
     * @return string
     */
    public function getNamaProgramStudi()
    {
        $programStudiList = [
            // D3
            '461' => 'Komputerisasi Akuntansi',
            '462' => 'Manajemen Informatika',
            
            // D4
            '471' => 'Teknik Informatika',
            '472' => 'Sistem Informasi',
            
            // S1
            '250' => 'Pendidikan Teknologi Informasi',
            '251' => 'Teknik Informatika',
            '252' => 'Sistem Informasi',
            '253' => 'Teknik Komputer',
            
            // S2 (Magister)
            '911' => 'Magister Pendidikan Teknologi Informasi',
            '912' => 'Magister Teknik Informatika',
            '913' => 'Magister Sistem Informasi',
            
            // S3 (Doktoral)
            '921' => 'Doktor Ilmu Komputer',
            '922' => 'Doktor Teknologi Informasi',
        ];

        $kode = $this->program_studi;
        $nama = $programStudiList[$kode] ?? 'Program Studi Tidak Diketahui';
        
        return "{$nama} ({$kode})";
    }

    /**
     * Get formatted nama lengkap
     *
     * @return string
     */
    public function getFormattedNamaAttribute()
    {
        return ucwords(strtolower($this->nama_lengkap));
    }

    /**
     * Get age from tanggal lahir
     *
     * @return int
     */
    public function getAgeAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : 0;
    }

    /**
     * Get full alamat
     *
     * @return string
     */
    public function getFullLocationAttribute()
    {
        $parts = array_filter([
            $this->tempat_lahir,
            $this->alamat,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Relasi dengan tabel users
     * Setiap mahasiswa memiliki satu akun user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi dengan tabel dokumen_mahasiswa
     * Setiap mahasiswa memiliki satu set dokumen
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dokumen()
    {
        return $this->hasOne(DokumenMahasiswa::class, 'mahasiswa_id');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Set default jenis_pendaftaran jika tidak ada
        static::creating(function ($mahasiswa) {
            if (empty($mahasiswa->jenis_pendaftaran)) {
                $mahasiswa->jenis_pendaftaran = 'sarjana';
            }
        });
    }
}
