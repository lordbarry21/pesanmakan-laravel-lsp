<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('foods')->insert([
            [
                'name'        => 'Nasi Goreng Spesial',
                'category'    => 'Makanan',
                'price'       => 25000,
                'description' => 'Nasi goreng dengan telur mata sapi, ayam suwir, dan kerupuk renyah.',
                'image'       => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Mie Goreng Seafood',
                'category'    => 'Makanan',
                'price'       => 28000,
                'description' => 'Mie goreng pedas gurih dengan topping udang, cumi, dan sayuran segar.',
                'image'       => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Es Teh Manis',
                'category'    => 'Minuman',
                'price'       => 5000,
                'description' => 'Es teh melati segar dengan gula tebu asli.',
                'image'       => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Jus Alpukat',
                'category'    => 'Minuman',
                'price'       => 15000,
                'description' => 'Jus alpukat mentega murni dengan kental manis cokelat.',
                'image'       => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Kentang Goreng',
                'category'    => 'Cemilan',
                'price'       => 12000,
                'description' => 'Kentang goreng renyah dengan taburan bumbu keju gurih.',
                'image'       => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
