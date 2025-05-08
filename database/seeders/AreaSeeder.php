<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            [
                'name' => 'المعادي',
                'latitude' => 29.9603,
                'longitude' => 31.2591,
                'radius' => 5.0,
                'price_per_km' => 50.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'مدينة نصر',
                'latitude' => 30.0561,
                'longitude' => 31.3300,
                'radius' => 5.0,
                'price_per_km' => 30.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'الزمالك',
                'latitude' => 30.0650,
                'longitude' => 31.2240,
                'radius' => 4.0,
                'price_per_km' => 35.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'المهندسين',
                'latitude' => 30.0459,
                'longitude' => 31.2109,
                'radius' => 4.5,
                'price_per_km' => 32.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'التجمع الخامس',
                'latitude' => 30.0081,
                'longitude' => 31.4913,
                'radius' => 6.0,
                'price_per_km' => 40.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }
    }
}
