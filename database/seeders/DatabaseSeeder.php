<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Default Admin User
        User::firstOrCreate(
            ['email' => 'admin@tikako.com'],
            [
                'name' => 'Administrator Tikako',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // Create Dummy User for Testing purposes
        User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'Pelanggan Contoh',
                'password' => Hash::make('password123'),
                'role' => 'user',
            ]
        );
    }
}