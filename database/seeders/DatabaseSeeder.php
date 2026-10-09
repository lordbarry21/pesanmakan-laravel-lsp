<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Default untuk Pengujian Asesor LSP
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Admin Toko',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Data Master Makanan Awal
        $this->call([
            FoodSeeder::class,
        ]);
    }
}
