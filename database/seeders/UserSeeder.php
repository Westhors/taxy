<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\City;
use App\Models\District;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'test user',
            'email' => 'test@taxy.test',
            'phone' => '1234567890',
            'gender' => 'male',
            'password' => Hash::make('12345678'),
            'avatar' =>  fake()->imageUrl(),
            'email_verified_at' => now(),
            'first_login_at' => now(),
            'latitude' => 12.971598,
            'longitude' => 77.594566,
            'city_id' => City::first()->id,
            'district_id' => District::first()->id,
        ]);
    }
}