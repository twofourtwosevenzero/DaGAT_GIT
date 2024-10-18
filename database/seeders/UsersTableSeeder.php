<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'PRI_ID' => 1,
                'Position_ID' => 1,
                'name' => 'Deane Santos Jr.',
                'email' => 'governor@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'PRI_ID' => 2,
                'Position_ID' => 2,
                'name' => 'Samantha Locsin',
                'email' => 'vice_governor@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'PRI_ID' => 2,
                'Position_ID' => 3,
                'name' => 'Jinnelyn Corpin',
                'email' => 'secretary@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'PRI_ID' => 2,
                'Position_ID' => 4,
                'name' => 'Jay Marasigan',
                'email' => 'treasurer@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'PRI_ID' => 2,
                'Position_ID' => 5,
                'name' => 'Lilian Dawatan',
                'email' => 'auditor@example.com',
                'password' => Hash::make('password'),
            ],
        ]);
    }
}
