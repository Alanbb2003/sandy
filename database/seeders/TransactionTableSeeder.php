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
        $startDate = Carbon::create(2024, 10, 1);
        $endDate = Carbon::create(2025, 1, 8);
        
        // Get the last transaction code to increment
        $lastTransaction = DB::table('htrans')->orderByDesc('id')->first();
        $lastKodeTrans = $lastTransaction ? $lastTransaction->kodeTrans : 'TR00000';
        $lastNumber = (int) substr($lastKodeTrans, 2);
        
        // Canceled reasons
        $canceledReasons = [
            'Saya berubah pikiran',
            'Ada kesalahan dalam pemesanan',
            'Harga lebih murah di tempat lain'
        ];
        
        // Payment proof filenames
        $paymentProofs = [
            'TR00001.6749ddfd594f0.webp',
            'TR000046751ddfb609fswa.webp',
            'TR00005.6752ddfd609fsd.webp',
            'TR00009.67514b3295dcc.webp',
            'TR00010.6751463ad069a.webp',
            'TR00011.67514459350f4.webp',
            'TR00012.676036b927c3e.webp',
            'TR0006.6751463ac09a.webp',
            'TR01482.6749ddfd594f0.webp',
            'TR01483.6749ddfd594f0.webp',
            'TR01489.6749ddfd594f0.webp',
            'TR01490.6749ddfd594f0.webp',
            'TR01492.6749ddfd594f0.webp',
            'TR01495.6749ddfd594f0.webp',
            'TR01499.6751ddfb609fswa.webp',
            'TR01500.6751ddfb609fswa.webp',
            'TR01502.6751ddfb609fswa.webp',
            'TR01504.6751ddfb609fswa.webp',
            'TR01505.6749ddfd594f0.webp',
            'TR01509.6749ddfd594f0.webp',
            'TR01511.6749ddfff03943.webp'
        ];
        
        // Initialize the transaction date
        $currentDate = $startDate->copy();
        $totalTransactions = 0;
        $maxTransactions = 1500;
        
        while ($currentDate->lessThanOrEqualTo($endDate) && $totalTransactions < $maxTransactions) {
            $transactionsPerDay = rand(10, 20);
            for ($i = 0; $i < $transactionsPerDay && $totalTransactions < $maxTransactions; $i++) {
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
                $status = rand(1, 100) <= 40 ? 4 : 0;
                $canceledReason = $status === 4 ? $faker->randomElement($canceledReasons) : null;
        
                // Randomly decide transaction status (10% chance of status 3)
                if (rand(1, 100) <= 50) {
                    $status = 3;
                    $buktiPembayaran = $faker->randomElement($paymentProofs);
                } else {
                    $buktiPembayaran = null;
                }
        
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
                    'buktiPembayaran' => $buktiPembayaran,
                    'survey_sent' => 0,
                    'fkPenyelesaian'=>1,
                    'CREATED_AT' => now(),
                    'UPDATED_AT' => now(),
                ]);
        
                $totalPembelian = 0;
        
                // Create random number of items per transaction
                $numItems = rand(1, 3);
                for ($j = 0; $j < $numItems; $j++) {
                    $product = $products->random();
        
                    // Ensure the product is a single object
                    $product = $product instanceof \Illuminate\Support\Collection ? $product->first() : $product;
        
                    $jumlah = rand(1, 2);
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
        
                // Ensure totalPembelian is at least 50,000
                while ($totalPembelian < 40000) {
                    $product = $products->random();
        
                    // Ensure the product is a single object
                    $product = $product instanceof \Illuminate\Support\Collection ? $product->first() : $product;
        
                    $jumlah = rand(1, 2);
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
                
                $totalTransactions++;
            }
        
            // Increment the transaction date by one day
            $currentDate->addDay();
        }
    }
}