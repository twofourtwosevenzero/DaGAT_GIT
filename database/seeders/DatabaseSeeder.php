<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PrivilegesTableSeeder::class,
            PositionsTableSeeder::class,
            UsersTableSeeder::class,
            DocumentTypesTableSeeder::class,
            StatusesTableSeeder::class,
            OfficesTableSeeder::class,
        ]);
    }
}
