<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Burger Paket', 'price' => 45000, 'icon' => '🍔'],
            ['name' => 'Kentang Goreng', 'price' => 15000, 'icon' => '🍟'],
            ['name' => 'Es Teh Manis', 'price' => 5000, 'icon' => '🥤'],
            ['name' => 'Es Krim', 'price' => 10000, 'icon' => '🍦'],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
