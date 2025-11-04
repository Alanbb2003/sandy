<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class WishlistTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        // Loop through each user (from id 4 to 13)
        for ($userID = 13; $userID <= 50; $userID++) {
            // Ensure that each user gets at least one product
            $productID = rand(1, 91); // Random product ID between 1 and 91
            DB::table('wishlist')->insert([
                'fkUserID' => $userID,
                'fkProductID' => $productID,
                'CREATED_AT' => now(),
                'UPDATED_AT' => now(),
            ]);

            // Optionally, add a few more products for each user
            $additionalProducts = rand(1, 3); // Random number of additional products (1 to 3)
            for ($i = 0; $i < $additionalProducts; $i++) {
                $productID = rand(1, 91); // Random product ID
                DB::table('wishlist')->insert([
                    'fkUserID' => $userID,
                    'fkProductID' => $productID,
                    'CREATED_AT' => now(),
                    'UPDATED_AT' => now(),
                ]);
            }
        }
    }
}
