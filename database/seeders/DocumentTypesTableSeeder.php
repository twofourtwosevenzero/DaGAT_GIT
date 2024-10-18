<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('document_types')->insert([
            ['DT_Type' => 'Activity Design'],
            ['DT_Type' => 'Letter'],
            ['DT_Type' => 'Planning Form'],
            ['DT_Type' => 'Financial Statement'],
            ['DT_Type' => 'Achievement Report'],
            ['DT_Type' => 'PubMat Request'],
            ['DT_Type' => 'Attendance Sheet'],
            ['DT_Type' => 'Notice of Meeting'],
            ['DT_Type' => 'Minutes of Meeting'],
        ]);
    }
}
