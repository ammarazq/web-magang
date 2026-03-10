<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cek apakah kolom kode_program_studi sudah ada
        if (!Schema::hasColumn('mahasiswa', 'kode_program_studi')) {
            // Tambahkan kolom baru untuk menyimpan kode program studi
            Schema::table('mahasiswa', function (Blueprint $table) {
                $table->string('kode_program_studi')->nullable()->after('program_studi');
            });
        }
        
        // Mapping kode ke nama program studi
        $programStudiList = [
            // D3
            '461' => 'Komputerisasi Akuntansi',
            '462' => 'Manajemen Informatika',
            
            // D4
            '471' => 'Teknik Informatika',
            '472' => 'Sistem Informasi',
            
            // S1
            '76' => 'Pendidikan Teknologi Informasi',
            '250' => 'Pendidikan Teknologi Informasi',
            '251' => 'Teknik Informatika',
            '252' => 'Sistem Informasi',
            '253' => 'Teknik Komputer',
            '279' => 'Teknik Informatika',
            '311' => 'Sistem Informasi',
            
            // S2 (Magister)
            '911' => 'Magister Pendidikan Teknologi Informasi',
            '912' => 'Magister Teknik Informatika',
            '913' => 'Magister Sistem Informasi',
            
            // S3 (Doktoral)
            '921' => 'Doktor Ilmu Komputer',
            '922' => 'Doktor Teknologi Informasi',
        ];
        
        // Update data yang sudah ada: pindahkan kode ke kode_program_studi dan isi nama ke program_studi
        DB::table('mahasiswa')->whereNotNull('program_studi')->orderBy('id')->chunk(100, function ($mahasiswaList) use ($programStudiList) {
            foreach ($mahasiswaList as $mahasiswa) {
                $kode = $mahasiswa->program_studi;
                
                // Hanya update jika program_studi masih berupa kode (numeric)
                if (is_numeric($kode)) {
                    $nama = $programStudiList[$kode] ?? null;
                    
                    if ($nama) {
                        DB::table('mahasiswa')
                            ->where('id', $mahasiswa->id)
                            ->update([
                                'kode_program_studi' => $kode,
                                'program_studi' => $nama,
                            ]);
                    }
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke format lama: pindahkan kode_program_studi ke program_studi
        DB::table('mahasiswa')->whereNotNull('kode_program_studi')->orderBy('id')->chunk(100, function ($mahasiswaList) {
            foreach ($mahasiswaList as $mahasiswa) {
                DB::table('mahasiswa')
                    ->where('id', $mahasiswa->id)
                    ->update([
                        'program_studi' => $mahasiswa->kode_program_studi,
                    ]);
            }
        });
        
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn('kode_program_studi');
        });
    }
};
