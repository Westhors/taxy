<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;

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
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            ]
        ];

        foreach ($drivers as $driver) {
            Driver::updateOrCreate([
                'name' => $driver['name'],
                'email' => $driver['email'],
                'password' => $driver['password'],
            ], $driver);
        }

        // Admin::factory()->count(5000)->create();

    }
}
