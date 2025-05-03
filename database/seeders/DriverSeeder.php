<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drivers = [
            [
                'name' => 'driver',
                'email' => 'driver@test-taxy.com',
                'password' => Hash::make('12345678'),
            ]
        ];

        foreach ($drivers as $driver) {
            Driver::updateOrCreate([
                'name' => $driver['name'],
                'email' => $driver['email'],
                'password' => $driver['password'],
                'latitude' => 12.971598,
                'longitude' => 77.594566,
            ], $driver);
        }

        // Admin::factory()->count(5000)->create();

    }
}