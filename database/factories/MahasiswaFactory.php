<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\Mahasiswa;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Mahasiswa::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenisPendaftaran = $this->faker->randomElement(['sarjana', 'magister', 'doktoral']);
        $kewarganegaraan = $this->faker->randomElement(['WNI', 'WNA']);
        $isWNI = $kewarganegaraan === 'WNI';
        
        $namaLengkap = $this->faker->name();
        $email = $this->faker->unique()->safeEmail();
        
        // Buat user terlebih dahulu
        $user = User::create([
            'name' => $namaLengkap,
            'email' => $email,
            'password' => Hash::make('password'),
        ]);

        $data = [
            // Relasi dengan users
            'user_id' => $user->id,
            
            // Data Pribadi
            'nama_lengkap' => $namaLengkap,
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->dateTimeBetween('-50 years', '-15 years'),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'agama' => $this->faker->randomElement(['Islam', 'Protestan', 'Katolik', 'Hindu', 'Budha', 'Konghucu']),
            'nama_ibu' => $this->faker->firstNameFemale() . ' ' . $this->faker->lastName(),
            'status_kawin' => $jenisPendaftaran === 'sarjana' ? null : $this->faker->randomElement(['Kawin', 'Belum Kawin']),
            
            // Data Kewarganegaraan
            'kewarganegaraan' => $kewarganegaraan,
            'nik' => $isWNI ? $this->faker->numerify('################') : null,
            'negara' => !$isWNI ? $this->faker->country() : null,
            'passport' => !$isWNI ? strtoupper($this->faker->bothify('??########')) : null,
            
            // Data Kontak
            'alamat' => $jenisPendaftaran === 'sarjana' ? $this->faker->address() : null,
            'no_hp' => '08' . $this->faker->numerify('##########'),
            'email' => $email,
            'password' => Hash::make('password'),
            
            // Data Akademik (khusus Sarjana)
            'jalur_program' => $jenisPendaftaran === 'sarjana' ? $this->faker->randomElement(['RPL', 'Non RPL']) : null,
            'jenjang' => $jenisPendaftaran === 'sarjana' ? $this->faker->randomElement(['D3', 'D4', 'S1']) : null,
            'program_studi' => $jenisPendaftaran === 'sarjana' ? $this->faker->randomElement([
                'Teknik Informatika',
                'Sistem Informasi',
                'Manajemen',
                'Akuntansi',
                'Hukum',
                'Pendidikan Bahasa Inggris'
            ]) : null,
            
            // Jenis Pendaftaran
            'jenis_pendaftaran' => $jenisPendaftaran,
            
            // Status
            'status_verifikasi' => $this->faker->randomElement(['pending', 'verified', 'rejected']),
            'catatan_verifikasi' => $this->faker->optional(0.3)->sentence(),
            
            // Metadata
            'email_verified_at' => $this->faker->optional(0.5)->dateTime(),
            'verified_by' => null,
            'verified_at' => null,
        ];

        return $data;
    }

    /**
     * Indicate that the mahasiswa is for sarjana program.
     */
    public function sarjana(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_pendaftaran' => 'sarjana',
            'jalur_program' => $this->faker->randomElement(['RPL', 'Non RPL']),
            'jenjang' => $this->faker->randomElement(['D3', 'D4', 'S1']),
            'program_studi' => $this->faker->randomElement([
                'Teknik Informatika',
                'Sistem Informasi',
                'Manajemen',
                'Akuntansi',
            ]),
            'alamat' => $this->faker->address(),
            'status_kawin' => null,
        ]);
    }

    /**
     * Indicate that the mahasiswa is for magister program.
     */
    public function magister(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_pendaftaran' => 'magister',
            'jalur_program' => null,
            'jenjang' => null,
            'program_studi' => null,
            'alamat' => null,
            'status_kawin' => $this->faker->randomElement(['Kawin', 'Belum Kawin']),
        ]);
    }

    /**
     * Indicate that the mahasiswa is for doktoral program.
     */
    public function doktoral(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_pendaftaran' => 'doktoral',
            'jalur_program' => null,
            'jenjang' => null,
            'program_studi' => null,
            'alamat' => null,
            'status_kawin' => $this->faker->randomElement(['Kawin', 'Belum Kawin']),
        ]);
    }

    /**
     * Indicate that the mahasiswa is verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_verifikasi' => 'verified',
            'email_verified_at' => now(),
            'verified_at' => now(),
            'verified_by' => 1,
        ]);
    }

    /**
     * Indicate that the mahasiswa is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_verifikasi' => 'pending',
            'verified_at' => null,
            'verified_by' => null,
        ]);
    }

    /**
     * Indicate that the mahasiswa is WNI.
     */
    public function wni(): static
    {
        return $this->state(fn (array $attributes) => [
            'kewarganegaraan' => 'WNI',
            'nik' => $this->faker->numerify('################'),
            'negara' => null,
            'passport' => null,
        ]);
    }

    /**
     * Indicate that the mahasiswa is WNA.
     */
    public function wna(): static
    {
        return $this->state(fn (array $attributes) => [
            'kewarganegaraan' => 'WNA',
            'nik' => null,
            'negara' => $this->faker->country(),
            'passport' => strtoupper($this->faker->bothify('??########')),
        ]);
    }
}
