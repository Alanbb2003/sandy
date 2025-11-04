<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class CreateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [ 'name'=>'user',
            'email'=>'user@contoh.com',
            'password'=>bcrypt('12345'),
            'role'=>0
            ],
            [ 'name'=>'admin',
            'email'=>'admin@contoh.com',
            'password'=>bcrypt('12345'),
            'role'=>1
            ]
        ];
        foreach($users as $user){
            User::create($user);
        }
        
    }
}
