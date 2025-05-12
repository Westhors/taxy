<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'name' => 'admin',
                'email' => 'admin@taxy.com',
                'password' => Hash::make('admin'),
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