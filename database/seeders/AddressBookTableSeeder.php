<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AddressBookTableSeeder extends Seeder
{
    public function run()
    {
        // Create a Faker instance with the 'id_ID' locale for Indonesian data
        $faker = Faker::create('id_ID');

        // Maluku province and unique Ambon city districts (kecamatan)
        $province = 'Maluku';
        $ambonDistricts = [
            'Sirimau' => 'Jl. Sirimau Raya No. 1, Ambon, Maluku',
            'Nusaniwe' => 'Jl. Nusaniwe Raya No. 2, Ambon, Maluku',
            'Baguala' => 'Jl. Baguala Raya No. 3, Ambon, Maluku',
            'Teluk Ambon' => 'Jl. Teluk Ambon No. 4, Ambon, Maluku',
            'Leitimur Selatan' => 'Jl. Leitimur Selatan No. 5, Ambon, Maluku',
            'Lateri' => 'Jl. Lateri Raya No. 6, Ambon, Maluku',
            'Hative Besar' => 'Jl. Hative Besar No. 7, Ambon, Maluku',
            'Batu Merah' => 'Jl. Batu Merah Raya No. 8, Ambon, Maluku',
            'Passo' => 'Jl. Passo Raya No. 9, Ambon, Maluku',
            'Poka' => 'Jl. Poka Raya No. 10, Ambon, Maluku',
            'Halong' => 'Jl. Halong Raya No. 11, Ambon, Maluku',
            'Galala' => 'Jl. Galala Raya No. 12, Ambon, Maluku',
            'Benteng' => 'Jl. Benteng Raya No. 13, Ambon, Maluku',
            'Amahusu' => 'Jl. Amahusu Raya No. 14, Ambon, Maluku',
            'Latuhalat' => 'Jl. Latuhalat Raya No. 15, Ambon, Maluku',
        ];

        // Fetch user data from the users table for user IDs 14 to 50
        $users = DB::table('users')
            ->whereBetween('id', [14, 51])
            ->get();

        // Loop through each user
        foreach ($users as $index => $user) {
            // Use modulo to loop through the Ambon districts if there are more users than districts
            $districtIndex = $index % count($ambonDistricts);
            $district = array_keys($ambonDistricts)[$districtIndex]; // Get the kecamatan (district) name
            $address = $ambonDistricts[$district]; // Get the address for the district

            // Insert into the address_book table
            DB::table('address_book')->insert([
                'fkUserID' => $user->id, // Use the user ID from the users table
                'namaDepan' => $user->firstName, // Get the firstName from the users table
                'namaBelakang' => $user->lastName, // Get the lastName from the users table
                'provinsi' => $province,
                'kota' => 'Ambon', // Set the city to Ambon
                'kecamatan' => $district, // Use the unique kecamatan
                'kelurahan' => $faker->streetName, // Generate a random kelurahan
                'kodePos' => $faker->postcode, // Random postcode
                'noHP' => $faker->phoneNumber, // Random phone number
                'detailAlamat' => $address, // Use the specific address for the kecamatan
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}