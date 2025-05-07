<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Country::updateOrCreate(
            ['country_code' => 'EG'],
            ['name' => 'مصر', 'price_per_km' => 25.0]
        );

        Country::updateOrCreate(
            ['country_code' => 'IQ'],
            ['name' => 'العراق', 'price_per_km' => 400.0]
        );
    }
}