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
            'email' => 'admin1@example.com',
            'password' => bcrypt('sama123'),
            'role' => 'admin',
        ]);

        // Create Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin2@example.com',
            'password' => bcrypt('123sama'),
            'role' => 'admin',
        ]);

        // Create Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin3@example.com',
            'password' => bcrypt('samasama'),
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
