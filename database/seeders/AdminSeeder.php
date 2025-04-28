<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Company;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'name' => 'adam',
                'email' => 'adam@wsa-network.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            ]
        ];

        foreach ($admins as $admin) {
            Admin::updateOrCreate([
                'name' => $admin['name'],
                'email' => $admin['email'],
                'password' => $admin['password'],
            ], $admin);
        }

        // Admin::factory()->count(5000)->create();

    }
}
