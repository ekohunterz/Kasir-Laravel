<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        \App\Models\Payment::create([
            'name' => 'Cash',
            'description' => 'Cash Payment',
        ]);

        \App\Models\Payment::create([
            'name' => 'QRIS',
            'description' => 'QRIS Payment',
            'is_cashless' => true,
        ]);
    }
}
