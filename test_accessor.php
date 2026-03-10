<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Mahasiswa;

$m = Mahasiswa::where('email', 'nadip@gmail.com')->first();

if ($m) {
    echo "=== Data Mahasiswa ===\n";
    echo "Nama: " . $m->nama_lengkap . "\n";
    echo "Email: " . $m->email . "\n";
    echo "Jenjang: " . $m->jenjang . "\n";
    echo "\n=== Program Studi ===\n";
    echo "Kode Program Studi (DB): " . ($m->kode_program_studi ?? 'NULL') . "\n";
    echo "Program Studi (DB): " . ($m->getAttributes()['program_studi'] ?? 'NULL') . "\n";
    echo "Nama Program Studi (Accessor): " . $m->nama_program_studi . "\n";
    echo "Program Studi Lengkap: " . $m->getProgramStudiLengkap() . "\n";
} else {
    echo "Mahasiswa tidak ditemukan\n";
}
