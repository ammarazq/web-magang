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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            
            // ============================================
            // DATA PRIBADI
            // ============================================
            $table->string('nama_lengkap');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->enum('agama', ['Islam', 'Protestan', 'Katolik', 'Hindu', 'Budha', 'Konghucu']);
            $table->string('nama_ibu');
            $table->enum('status_kawin', ['Kawin', 'Belum Kawin'])->nullable();
            
            // ============================================
            // DATA KEWARGANEGARAAN
            // ============================================
            $table->enum('kewarganegaraan', ['WNI', 'WNA']);
            $table->string('nik', 16)->nullable()->unique(); // untuk WNI
            $table->string('negara')->nullable(); // untuk WNA
            $table->string('passport', 15)->nullable()->unique(); // untuk WNA
            
            // ============================================
            // DATA KONTAK & ALAMAT
            // ============================================
            $table->text('alamat')->nullable();
            $table->string('no_hp', 15);
            $table->string('email')->unique();
            $table->string('password');
            
            // ============================================
            // DATA AKADEMIK (khusus Sarjana)
            // ============================================
            $table->enum('jalur_program', ['RPL', 'Non RPL'])->nullable();
            $table->enum('jenjang', ['D3', 'D4', 'S1'])->nullable();
            $table->string('program_studi')->nullable();
            
            // ============================================
            // JENIS PENDAFTARAN
            // ============================================
            $table->enum('jenis_pendaftaran', ['sarjana', 'magister', 'doktoral'])->default('sarjana')->index();
            
            // ============================================
            // STATUS & VERIFIKASI
            // ============================================
            $table->enum('status_verifikasi', ['pending', 'verified', 'rejected'])->default('pending')->index();
            $table->text('catatan_verifikasi')->nullable();
            
            // ============================================
            // METADATA
            // ============================================
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable(); // ID admin yang verifikasi
            $table->timestamp('verified_at')->nullable();
            
            // ============================================
            // TIMESTAMPS & SOFT DELETE
            // ============================================
            $table->timestamps();
            $table->softDeletes();
            
            // ============================================
            // INDEXES untuk performa query
            // ============================================
            $table->index('email');
            $table->index('nik');
            $table->index('passport');
            $table->index(['jenis_pendaftaran', 'status_verifikasi']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
