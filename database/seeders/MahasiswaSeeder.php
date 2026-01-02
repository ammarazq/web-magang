<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data (optional)
        // Mahasiswa::truncate();

        // Create Sarjana mahasiswa
        Mahasiswa::factory()
            ->count(30)
            ->sarjana()
            ->create();

        // Create Magister mahasiswa
        Mahasiswa::factory()
            ->count(20)
            ->magister()
            ->create();

        // Create Doktoral mahasiswa
        Mahasiswa::factory()
            ->count(10)
            ->doktoral()
            ->create();

        // Create some verified mahasiswa
        Mahasiswa::factory()
            ->count(15)
            ->verified()
            ->sarjana()
            ->create();

        Mahasiswa::factory()
            ->count(10)
            ->verified()
            ->magister()
            ->create();

        Mahasiswa::factory()
            ->count(5)
            ->verified()
            ->doktoral()
            ->create();

        // Create specific test accounts
        Mahasiswa::create([
            'nama_lengkap' => 'Test User Sarjana',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'nama_ibu' => 'Ibu Test',
            'kewarganegaraan' => 'WNI',
            'nik' => '1234567890123456',
            'alamat' => 'Jl. Test No. 123',
            'no_hp' => '081234567890',
            'email' => 'test.sarjana@example.com',
            'password' => bcrypt('password'),
            'jalur_program' => 'RPL',
            'jenjang' => 'S1',
            'program_studi' => 'Teknik Informatika',
            'jenis_pendaftaran' => 'sarjana',
            'status_verifikasi' => 'pending',
        ]);

        Mahasiswa::create([
            'nama_lengkap' => 'Test User Magister',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1995-05-15',
            'jenis_kelamin' => 'P',
            'agama' => 'Katolik',
            'nama_ibu' => 'Ibu Magister',
            'status_kawin' => 'Belum Kawin',
            'kewarganegaraan' => 'WNI',
            'nik' => '9876543210987654',
            'no_hp' => '089876543210',
            'email' => 'test.magister@example.com',
            'password' => bcrypt('password'),
            'jenis_pendaftaran' => 'magister',
            'status_verifikasi' => 'verified',
            'email_verified_at' => now(),
        ]);

        Mahasiswa::create([
            'nama_lengkap' => 'Test User WNA',
            'tempat_lahir' => 'Singapore',
            'tanggal_lahir' => '1998-12-25',
            'jenis_kelamin' => 'L',
            'agama' => 'Hindu',
            'nama_ibu' => 'Mother WNA',
            'kewarganegaraan' => 'WNA',
            'negara' => 'Singapore',
            'passport' => 'SG12345678',
            'alamat' => '123 Marina Bay',
            'no_hp' => '087654321098',
            'email' => 'test.wna@example.com',
            'password' => bcrypt('password'),
            'jalur_program' => 'Non RPL',
            'jenjang' => 'S1',
            'program_studi' => 'Manajemen',
            'jenis_pendaftaran' => 'sarjana',
            'status_verifikasi' => 'pending',
        ]);

        $this->command->info('Mahasiswa data seeded successfully!');
        $this->command->info('Total: ' . Mahasiswa::count() . ' mahasiswa created.');
        $this->command->info('Sarjana: ' . Mahasiswa::sarjana()->count());
        $this->command->info('Magister: ' . Mahasiswa::magister()->count());
        $this->command->info('Doktoral: ' . Mahasiswa::doktoral()->count());
    }
}
