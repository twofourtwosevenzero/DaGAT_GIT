<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('offices')->insert([
            ['Office_Name' => 'Office of the President', 'Office_Pin' => 'ADM001'],
            ['Office_Name' => 'Office of the Vice President for Academic Affairs', 'Office_Pin' => 'ADM002'],
            ['Office_Name' => 'Office of the Vice President for Administration', 'Office_Pin' => 'ADM003'],
            ['Office_Name' => 'Office of the Vice President for Planning and Quality Assurance', 'Office_Pin' => 'ADM004'],
            ['Office_Name' => 'Office of the Vice President for Research, Development and Extension', 'Office_Pin' => 'ADM005'],
            ['Office_Name' => 'Office of the Secretary of the University and the University Records Office', 'Office_Pin' => 'ADM006'],
            ['Office_Name' => 'Office of Legal Affairs', 'Office_Pin' => 'ADM007'],
            ['Office_Name' => 'International Affairs Division', 'Office_Pin' => 'ADM008'],
            ['Office_Name' => 'Public Affairs Division', 'Office_Pin' => 'ADM009'],
            ['Office_Name' => 'Office of Advance Studies', 'Office_Pin' => 'ADM010'],
            ['Office_Name' => 'Human Resource Management Division', 'Office_Pin' => 'ADM011'],
            ['Office_Name' => 'Administrative Services Division', 'Office_Pin' => 'ADM012'],
            ['Office_Name' => 'Physical Development Division', 'Office_Pin' => 'ADM013'],
            ['Office_Name' => 'Gender and Development Office', 'Office_Pin' => 'ADM014'],
            ['Office_Name' => 'Bids & Awards Committee', 'Office_Pin' => 'ADM015'],
            ['Office_Name' => 'Office of Student Affairs and Services', 'Office_Pin' => 'SER001'],
            ['Office_Name' => 'Office of the University Registrar', 'Office_Pin' => 'SER002'],
            ['Office_Name' => 'University Assessment and Guidance Center', 'Office_Pin' => 'SER003'],
            ['Office_Name' => 'University Learning Resource Center', 'Office_Pin' => 'SER004'],
            ['Office_Name' => 'Resource Management Division', 'Office_Pin' => 'SER005'],
            ['Office_Name' => 'Health Services Division', 'Office_Pin' => 'SER006'],
            ['Office_Name' => 'University Finance Division', 'Office_Pin' => 'SER007'],
            ['Office_Name' => 'Research, Development and Extension', 'Office_Pin' => 'SER008'],
            ['Office_Name' => 'College of Applied Economics', 'Office_Pin' => 'COL001'],
            ['Office_Name' => 'College of Arts and Sciences', 'Office_Pin' => 'COL002'],
            ['Office_Name' => 'College of Business Administration', 'Office_Pin' => 'COL003'],
            ['Office_Name' => 'College of Education', 'Office_Pin' => 'COL004'],
            ['Office_Name' => 'College of Engineering', 'Office_Pin' => 'COL005'],
            ['Office_Name' => 'College of Information and Computing', 'Office_Pin' => 'COL006'],
            ['Office_Name' => 'College of Technology', 'Office_Pin' => 'COL007'],
            ['Office_Name' => 'College of Applied Economics LC', 'Office_Pin' => 'CLC001'],
            ['Office_Name' => 'College of Arts and Sciences LC', 'Office_Pin' => 'CLC002'],
            ['Office_Name' => 'College of Business Administration LC', 'Office_Pin' => 'CLC003'],
            ['Office_Name' => 'College of Education LC', 'Office_Pin' => 'CLC004'],
            ['Office_Name' => 'College of Engineering LC', 'Office_Pin' => 'CLC005'],
            ['Office_Name' => 'College of Information and Computing LC', 'Office_Pin' => 'CLC006'],
            ['Office_Name' => 'College of Technology LC', 'Office_Pin' => 'CLC007'],
        ]);
    }
}
