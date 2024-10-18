<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrivilegesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('privileges')->insert([
            ['Privilege_Level' => 'Administrator'],
            ['Privilege_Level' => 'Officer'],
        ]);
    }
}
