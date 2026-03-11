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
            // Kolom untuk menyimpan folder ID di Google Drive (satu folder per mahasiswa)
            $table->string('google_drive_folder_id')->nullable()->after('verified_at');
            
            // Kolom JSON untuk menyimpan mapping file_name => google_drive_file_id
            $table->json('google_drive_files')->nullable()->after('google_drive_folder_id');
            
            // Flag untuk menandai apakah sudah di-backup ke Google Drive
            $table->boolean('is_backed_up')->default(false)->after('google_drive_files');
            
            // Timestamp terakhir backup
            $table->timestamp('last_backup_at')->nullable()->after('is_backed_up');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen_mahasiswa', function (Blueprint $table) {
            $table->dropColumn([
                'google_drive_folder_id',
                'google_drive_files',
                'is_backed_up',
                'last_backup_at'
            ]);
        });
    }
};
