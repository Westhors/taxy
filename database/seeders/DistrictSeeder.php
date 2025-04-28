<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            [
                "name" => "District 1",
            ],
            [
                "name" => "District 2",
            ],
            [
                "name" => "District 3",
            ],
            [
                "name" => "District 4",
            ],
        ];

        foreach ($districts as $district) {
            District::create($district);
        }
    }
}