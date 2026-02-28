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
        Schema::table('dokumen_mahasiswa', function (Blueprint $table) {
            $table->string('berkas_dokumen_pendaftaran')->nullable()->after('formulir_isian_foto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen_mahasiswa', function (Blueprint $table) {
            $table->dropColumn('berkas_dokumen_pendaftaran');
        });
    }
};
