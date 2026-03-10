<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Mahasiswa;

echo "=== Verifikasi 10 Data Mahasiswa ===\n\n";

Mahasiswa::take(10)->get()->each(function($m) {
    echo "ID {$m->id}: {$m->jenjang} - {$m->nama_program_studi} ({$m->kode_program_studi})\n";
});

echo "\n=== Selesai ===\n";
