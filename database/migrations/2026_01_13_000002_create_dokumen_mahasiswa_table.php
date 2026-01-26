<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dokumen_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswa')->onDelete('cascade');
            
            // Dokumen Umum (untuk semua jenjang)
            $table->string('formulir_pendaftaran')->nullable(); // jpg/pdf
            $table->string('formulir_keabsahan')->nullable(); // jpg/pdf (Lembar Keabsahan Dokumen)
            $table->string('foto_formal')->nullable(); // jpg
            $table->string('ktp')->nullable(); // jpg
            $table->string('ijazah_slta')->nullable(); // pdf (FC Ijazah SLTA Legalisir)
            
            // Dokumen untuk S1 RPL
            $table->string('ijazah_slta_asli')->nullable(); // pdf (Ijazah SLTA Asli - khusus RPL)
            $table->string('transkrip_nilai')->nullable(); // pdf (Transkrip Nilai D3/D4/S1 - khusus RPL)
            $table->string('ijazah_d3_d4_s1')->nullable(); // pdf (FC Ijazah D3/D4/S1 Legalisir - jika sudah lulus)
            
            // Dokumen untuk S2
            $table->string('sertifikat_akreditasi_prodi')->nullable(); // pdf (Sertifikat Akreditasi Prodi D4/S1)
            $table->string('transkrip_d3_d4_s1')->nullable(); // pdf (FC Transkrip Nilai D4/S1 Legalisir)
            $table->string('sertifikat_toefl')->nullable(); // pdf (min. 450 untuk S2, 500 untuk S3)
            $table->string('rancangan_penelitian')->nullable(); // pdf (Rancangan Penelitian Singkat)
            $table->string('sk_mampu_komputer')->nullable(); // pdf (SK Mampu menggunakan Komputer)
            $table->string('bukti_tes_tpa')->nullable(); // pdf (Bukti telah mengikuti Tes Potensi Akademik)
            $table->string('seleksi_tes_substansi')->nullable(); // pdf (Mengikuti Seleksi Tes Substansi)
            $table->string('formulir_isian_foto')->nullable(); // jpg/pdf (Formulir Isian Foto)
            $table->string('riwayat_hidup')->nullable(); // pdf (Daftar Riwayat Hidup)
            
            // Dokumen untuk S3
            $table->string('ijazah_s2')->nullable(); // pdf (FC Ijazah S2 Legalisir)
            $table->string('transkrip_s2')->nullable(); // pdf (FC Transkrip Nilai S2 Legalisir)
            $table->string('sertifikat_akreditasi_s2')->nullable(); // pdf (Sertifikat Akreditasi Prodi S2)
            
            // Status kelengkapan dokumen
            $table->enum('status_dokumen', ['belum_lengkap', 'lengkap', 'diverifikasi', 'ditolak'])->default('belum_lengkap');
            $table->text('catatan_verifikasi')->nullable();
            
            // Admin yang memverifikasi
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index('mahasiswa_id');
            $table->index('status_dokumen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_mahasiswa');
    }
};
