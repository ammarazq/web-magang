<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

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

// Reverse mapping untuk nama ke kode
$namaToKode = [];
foreach ($programStudiList as $kode => $nama) {
    if (!isset($namaToKode[$nama])) {
        $namaToKode[$nama] = $kode;
    }
}

echo "Memproses data mahasiswa...\n";

$mahasiswas = DB::table('mahasiswa')->whereNotNull('program_studi')->get();
$updated = 0;
$skipped = 0;

foreach ($mahasiswas as $mhs) {
    $programStudi = $mhs->program_studi;
    $kodeProgram = $mhs->kode_program_studi;
    
    // Jika kode_program_studi sudah ada dan valid, skip
    if (!empty($kodeProgram) && isset($programStudiList[$kodeProgram])) {
        $skipped++;
        continue;
    }
    
    // Jika program_studi adalah kode yang valid
    if (isset($programStudiList[$programStudi])) {
        $nama = $programStudiList[$programStudi];
        DB::table('mahasiswa')->where('id', $mhs->id)->update([
            'kode_program_studi' => $programStudi,
            'program_studi' => $nama,
        ]);
        echo "ID {$mhs->id}: Kode {$programStudi} -> {$nama}\n";
        $updated++;
    }
    // Jika program_studi adalah nama yang ada di daftar
    elseif (isset($namaToKode[$programStudi])) {
        $kode = $namaToKode[$programStudi];
        DB::table('mahasiswa')->where('id', $mhs->id)->update([
            'kode_program_studi' => $kode,
        ]);
        echo "ID {$mhs->id}: {$programStudi} -> Kode {$kode}\n";
        $updated++;
    }
    // Jika tidak ditemukan, set default berdasarkan jenjang
    else {
        $defaultKode = null;
        $defaultNama = null;
        
        switch ($mhs->jenjang) {
            case 'D3':
                $defaultKode = '461';
                $defaultNama = 'Komputerisasi Akuntansi';
                break;
            case 'D4':
                $defaultKode = '471';
                $defaultNama = 'Teknik Informatika';
                break;
            case 'S1':
                $defaultKode = '251';
                $defaultNama = 'Teknik Informatika';
                break;
            case 'S2':
                $defaultKode = '911';
                $defaultNama = 'Magister Pendidikan Teknologi Informasi';
                break;
            case 'S3':
                $defaultKode = '921';
                $defaultNama = 'Doktor Ilmu Komputer';
                break;
        }
        
        if ($defaultKode) {
            DB::table('mahasiswa')->where('id', $mhs->id)->update([
                'kode_program_studi' => $defaultKode,
                'program_studi' => $defaultNama,
            ]);
            echo "ID {$mhs->id}: Tidak ditemukan '{$programStudi}' -> Default {$defaultNama} ({$defaultKode})\n";
            $updated++;
        }
    }
}

echo "\n=== Selesai ===\n";
echo "Total diupdate: {$updated}\n";
echo "Total diskip: {$skipped}\n";
