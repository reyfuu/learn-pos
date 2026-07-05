<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transaction = \App\Models\Transaction::create([
            'invoice_number' => 'INV-' . time(),
            'subtotal' => 60000,
            'tax' => 6600,
            'grand_total' => 66600,
            'created_at' => now()->subDays(1)
        ]);

        $transaction->details()->createMany([
            ['product_id' => 1, 'quantity' => 1, 'price' => 45000],
            ['product_id' => 2, 'quantity' => 1, 'price' => 15000],
        ]);
    }
}
