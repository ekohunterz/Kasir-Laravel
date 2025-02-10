<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'user_id'       => 1,
            'favicon'       => null,
            'logo'          => null,
            'name'          => 'Cashier App',
            'short_name'    => 'C-APP',
            'tax'           => 11,
            'Description'   => 'Cashier App is a simple web-based point of sale system.',
        ]);
    }
}
