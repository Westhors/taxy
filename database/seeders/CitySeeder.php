<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            [
                "name" => "City 1",
            ],
            [
                "name" => "City 2",
            ],
            [
                "name" => "City 3",
            ],
            [
                "name" => "City 4",
            ],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}