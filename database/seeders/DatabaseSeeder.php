<?php

namespace Database\Seeders;

use App\Models\InkubisStage;
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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin 01',
            'email' => 'admin01@example.com',
            'role' => 'admin',
        ]);
        User::factory()->create([
            'name' => 'Tenant 01',
            'email' => 'tenant01@example.com',
        ]);

        InkubisStage::factory()->create([
            'tahapan_inkubasi' => 'Pra-Kewirausahaan',
            'deskripsi' => 'Tahap awal untuk mengembangkan ide bisnis.',
        ]);
        InkubisStage::factory()->create([
            'tahapan_inkubasi' => 'Pra-Inkubasi',
            'deskripsi' => 'Tahap awal untuk Seleksi Calon Tenant.',
        ]);
        InkubisStage::factory()->create([
            'tahapan_inkubasi' => 'Masa-Inkubasi',
            'deskripsi' => 'Tahap Klasikal dan Pembinaan Tenant.',
        ]);
        InkubisStage::factory()->create([
            'tahapan_inkubasi' => 'Pasca-Inkubasi',
            'deskripsi' => 'Tahap Monitoring dan Pelepasan menjadi Alumni Tenant.',
        ]);

        
    }
}
