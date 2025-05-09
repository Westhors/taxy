<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        Address::create([
            'user_id'   => $user->id,
            'name'      => 'Home',
            'latitude'  => 25.276987,
            'longitude' => 55.296249,
            'details'   => 'Apartment 12B, Marina View Towers, Dubai Marina',
        ]);

        Address::create([
            'user_id'   => $user->id,
            'name'      => 'Work',
            'latitude'  => 25.204849,
            'longitude' => 55.270783,
            'details'   => 'Office 302, Emirates Tower, Sheikh Zayed Road',
        ]);
    }
}