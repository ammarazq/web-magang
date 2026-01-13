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
            
            // Dokumen untuk Non RPL
            $table->string('ijazah_slta')->nullable(); // pdf
            $table->string('foto_formal')->nullable(); // jpg
            $table->string('ktp')->nullable(); // jpg
            $table->string('formulir_keabsahan')->nullable(); // jpg
            $table->string('formulir_pendaftaran')->nullable(); // jpg
            
            // Dokumen tambahan untuk RPL
            $table->string('ijazah_pendidikan_terakhir')->nullable(); // pdf
            $table->string('transkrip_nilai')->nullable(); // pdf
            $table->string('ijazah_slta_asli')->nullable(); // pdf (khusus RPL)
            $table->string('riwayat_hidup')->nullable(); // pdf
            
            // Status kelengkapan dokumen
            $table->enum('status_dokumen', ['belum_lengkap', 'lengkap', 'diverifikasi', 'ditolak'])->default('belum_lengkap');
            $table->text('catatan_verifikasi')->nullable();
            
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
