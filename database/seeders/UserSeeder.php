<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID'); 

        for ($i = 0; $i < 10; $i++) {
            $user = [
                'name' => $faker->name,
                'email' => $faker->unique()->userName . '@gmail.com',
                'password' => Hash::make('password123'),
                'firstName' => $faker->firstName,
                'lastName' => $faker->lastName,
                'role' => 0, // Customer role
                'tanggalLahir' => $faker->date('Y-m-d', '2000-01-01'),
                'noHp' => '0812' . $faker->randomNumber(7, true),
            ];

            $existingUser = User::where('email', $user['email'])->first();

            if (!$existingUser) {
                User::create($user);
            }
        }
    }
}