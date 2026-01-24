<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // Create Test Mahasiswa User
        User::factory()->create([
            'name' => 'Test Mahasiswa',
            'email' => 'mahasiswa@example.com',
            'password' => bcrypt('mahasiswa123'),
            'role' => 'mahasiswa',
        ]);

        $this->call([
            MahasiswaSeeder::class,
        ]);
    }
}
