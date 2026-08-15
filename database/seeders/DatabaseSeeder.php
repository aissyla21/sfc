<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TrainingLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin SFC',
            'nia' => 'SFC-000',
            'email' => 'admin@sfc.com',
            'password' => Hash::make('password123'),
            'role' => 'pelatih'
        ]);

        TrainingLocation::create([
            'name' => 'Kedai Ibu Dina',
            'latitude' => -6.3491520,
            'longitude' => 106.7651687,
            'radius_meter' => 1000
        ]);
    }
}
