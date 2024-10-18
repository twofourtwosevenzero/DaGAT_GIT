<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('positions')->insert([
            ['Position_Name' => 'Governor'],
            ['Position_Name' => 'Vice Governor'],
            ['Position_Name' => 'Secretary'],
            ['Position_Name' => 'Treasurer'],
            ['Position_Name' => 'Auditor'],
        ]);
    }
}
