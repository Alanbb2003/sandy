<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class TransactionTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Fetch user addresses for user IDs 13-50
        $usersAddresses = DB::table('address_book')
            ->whereIn('fkUserID', range(13, 50))
            ->get();

        // Fetch products with ID from 1 to 91
        $products = DB::table('product')
            ->whereBetween('id', [1, 91])
            ->get();

        // Maluku addresses
        $malukuCities = [
            'Ambon' => 'Jl. Merdeka No. 1, Ambon City, Maluku',
            'Tual' => 'Jl. Raya Tual No. 2, Tual City, Maluku Tenggara',
            'Saumlaki' => 'Jl. Saumlaki No. 3, Saumlaki City, Maluku Barat Daya',
            'Masohi' => 'Jl. Masohi No. 4, Masohi City, Maluku Tengah',
            'Langgur' => 'Jl. Langgur No. 5, Langgur City, Maluku Tenggara',
            'Dobo' => 'Jl. Dobo No. 6, Dobo City, Kepulauan Aru',
            'Namlea' => 'Jl. Namlea No. 7, Namlea City, Buru',
            'Bula' => 'Jl. Bula No. 8, Bula City, Seram Timur',
        ];

        // Specify the date range
        $startDate = Carbon::create(2024, 10, 5);
        $endDate = Carbon::create(2024, 12, 31);

        // Get the last transaction code to increment
        $lastTransaction = DB::table('htrans')->orderByDesc('id')->first();
        $lastKodeTrans = $lastTransaction ? $lastTransaction->kodeTrans : 'TR00000';
        $lastNumber = (int) substr($lastKodeTrans, 2);

        // Number of transactions to create
        $numTransactions = 1500;

        // Canceled reasons
        $canceledReasons = [
            'Saya berubah pikiran',
            'Ada kesalahan dalam pemesanan',
            'Harga lebih murah di tempat lain'
        ];

        // Initialize the transaction date
        $currentDate = $startDate->copy();

        for ($i = 0; $i < $numTransactions; $i++) {
            // Increment transaction number
            $transactionNumber = $lastNumber + 1;
            $kodeTrans = 'TR' . str_pad($transactionNumber, 5, '0', STR_PAD_LEFT);
            $lastNumber = $transactionNumber;

            // Select a random user address
            $address = $usersAddresses->random();
            $user = DB::table('users')->where('id', $address->fkUserID)->first();

            // Generate buyer details
            $namaPembeli = $user->firstName . ' ' . $user->lastName;
            $phoneNumber = '08' . rand(100000000, 999999999);

            // Randomly pick a Maluku city for the address snapshot
            $cityIndex = rand(0, count($malukuCities) - 1);
            $city = array_keys($malukuCities)[$cityIndex];
            $addressDetails = $malukuCities[$city];

            // Combine the address details
            $addressSnapshot = "$namaPembeli, $phoneNumber, $addressDetails, " .
                $faker->postcode . ", MALUKU, " .
                strtoupper($faker->city) . ", " .
                strtoupper($faker->city) . ", " .
                strtoupper($faker->city);

            // Assign the current transaction date
            $transactionDate = $currentDate->copy();

            // Randomly decide transaction status (35% chance of canceled)
            $status = rand(1, 100) <= 35 ? 4 : 0;
            $canceledReason = $status === 4 ? $faker->randomElement($canceledReasons) : null;

            // Create Htrans record
            $htransID = DB::table('htrans')->insertGetId([
                'kodeTrans' => $kodeTrans,
                'fkUserID' => $address->fkUserID,
                'namaPembeli' => $namaPembeli,
                'addressSnapshot' => $addressSnapshot,
                'tanggalPembelian' => $transactionDate,
                'totalPembelian' => 0, // Updated later
                'status' => $status,
                'alasanBatal' => $canceledReason,
                'survey_sent' => 0,
                'CREATED_AT' => now(),
                'UPDATED_AT' => now(),
            ]);

            $totalPembelian = 0;

            // Create random number of items per transaction
            $numItems = rand(1, 5);
            for ($j = 0; $j < $numItems; $j++) {
                $product = $products->random();

                // Ensure the product is a single object
                $product = $product instanceof \Illuminate\Support\Collection ? $product->first() : $product;

                $jumlah = rand(1, 3);
                $satuanBarang = $product->satuanTerkecil;
                $hargaSatuan = $product->hargaKecil;
                $subtotal = $jumlah * $hargaSatuan;

                DB::table('dtrans')->insert([
                    'fkHtransID' => $htransID,
                    'fkProductID' => $product->id,
                    'totalJumlah' => $jumlah,
                    'satuanBarang' => $satuanBarang,
                    'hargaSatuan' => $hargaSatuan,
                    'subtotal' => $subtotal,
                    'CREATED_AT' => now(),
                    'UPDATED_AT' => now(),
                ]);

                $totalPembelian += $subtotal;
            }

            // Update totalPembelian in Htrans table (for all statuses)
            DB::table('htrans')
                ->where('id', $htransID)
                ->update(['totalPembelian' => $totalPembelian]);

            // Increment the transaction date (ensuring it's within the range)
            $currentDate->addHours(rand(6, 12));
            if ($currentDate->greaterThan($endDate)) {
                $currentDate = $endDate->copy();
            }
        }
    }
}