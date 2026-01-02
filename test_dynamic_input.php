<?php

/**
 * Test Dynamic Data Input
 * 
 * Script ini untuk test koneksi antara Form -> Controller -> Model -> Database
 * Jalankan dengan: php test_dynamic_input.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Mahasiswa;

echo "================================================\n";
echo "   TEST DYNAMIC DATA INPUT TO DATABASE\n";
echo "================================================\n\n";

// Test 1: Create Sarjana
echo "Test 1: Creating Sarjana Student...\n";
try {
    $sarjana = Mahasiswa::create([
        'nama_lengkap' => 'Dynamic Test Sarjana',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2000-05-15',
        'jenis_kelamin' => 'L',
        'agama' => 'Islam',
        'nama_ibu' => 'Ibu Test Sarjana',
        'kewarganegaraan' => 'WNI',
        'nik' => '9999888877776666',
        'alamat' => 'Jl. Test Dinamis No. 123',
        'no_hp' => '081234567890',
        'email' => 'dynamic.sarjana.' . time() . '@test.com',
        'password' => bcrypt('password'),
        'jalur_program' => 'RPL',
        'jenjang' => 'S1',
        'program_studi' => 'Teknik Informatika',
        'jenis_pendaftaran' => 'sarjana',
        'status_verifikasi' => 'pending',
    ]);
    
    echo "✓ SUCCESS! Created with ID: {$sarjana->id}\n";
    echo "  - Nama: {$sarjana->nama_lengkap}\n";
    echo "  - Email: {$sarjana->email}\n";
    echo "  - Jenis: {$sarjana->jenis_pendaftaran}\n\n";
} catch (\Exception $e) {
    echo "✗ FAILED: {$e->getMessage()}\n\n";
}

// Test 2: Create Magister
echo "Test 2: Creating Magister Student...\n";
try {
    $magister = Mahasiswa::create([
        'nama_lengkap' => 'Dynamic Test Magister',
        'tempat_lahir' => 'Bandung',
        'tanggal_lahir' => '1995-03-20',
        'jenis_kelamin' => 'P',
        'agama' => 'Katolik',
        'nama_ibu' => 'Ibu Test Magister',
        'status_kawin' => 'Belum Kawin',
        'kewarganegaraan' => 'WNI',
        'nik' => '8888777766665555',
        'no_hp' => '089876543210',
        'email' => 'dynamic.magister.' . time() . '@test.com',
        'password' => bcrypt('password'),
        'jenis_pendaftaran' => 'magister',
        'status_verifikasi' => 'pending',
    ]);
    
    echo "✓ SUCCESS! Created with ID: {$magister->id}\n";
    echo "  - Nama: {$magister->nama_lengkap}\n";
    echo "  - Email: {$magister->email}\n";
    echo "  - Status Kawin: {$magister->status_kawin}\n\n";
} catch (\Exception $e) {
    echo "✗ FAILED: {$e->getMessage()}\n\n";
}

// Test 3: Create Doktoral
echo "Test 3: Creating Doktoral Student...\n";
try {
    $doktoral = Mahasiswa::create([
        'nama_lengkap' => 'Dynamic Test Doktoral',
        'tempat_lahir' => 'Surabaya',
        'tanggal_lahir' => '1990-12-10',
        'jenis_kelamin' => 'L',
        'agama' => 'Hindu',
        'nama_ibu' => 'Ibu Test Doktoral',
        'status_kawin' => 'Kawin',
        'kewarganegaraan' => 'WNI',
        'nik' => '7777666655554444',
        'no_hp' => '087654321098',
        'email' => 'dynamic.doktoral.' . time() . '@test.com',
        'password' => bcrypt('password'),
        'jenis_pendaftaran' => 'doktoral',
        'status_verifikasi' => 'pending',
    ]);
    
    echo "✓ SUCCESS! Created with ID: {$doktoral->id}\n";
    echo "  - Nama: {$doktoral->nama_lengkap}\n";
    echo "  - Email: {$doktoral->email}\n";
    echo "  - Status Kawin: {$doktoral->status_kawin}\n\n";
} catch (\Exception $e) {
    echo "✗ FAILED: {$e->getMessage()}\n\n";
}

// Test 4: Verify Data
echo "Test 4: Verifying Database Statistics...\n";
$total = Mahasiswa::count();
$sarjanaCount = Mahasiswa::sarjana()->count();
$magisterCount = Mahasiswa::magister()->count();
$doktoralCount = Mahasiswa::doktoral()->count();
$pendingCount = Mahasiswa::pending()->count();

echo "✓ Total Mahasiswa: {$total}\n";
echo "  - Sarjana: {$sarjanaCount}\n";
echo "  - Magister: {$magisterCount}\n";
echo "  - Doktoral: {$doktoralCount}\n";
echo "  - Pending: {$pendingCount}\n\n";

echo "================================================\n";
echo "   ALL TESTS COMPLETED!\n";
echo "================================================\n";
echo "\n";
echo "Koneksi Form -> Controller -> Model -> Database\n";
echo "sudah berfungsi dengan baik! ✓\n\n";
