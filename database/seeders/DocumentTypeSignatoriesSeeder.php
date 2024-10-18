<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypeSignatoriesSeeder extends Seeder
{
    public function run()
    {
        // Activity Design (ID = 1)
        DB::table('document_type_signatories')->insert([
            ['document_type_id' => 1, 'signatory_id' => 36, 'created_at' => now(), 'updated_at' => now()], // CIC Local Council
            ['document_type_id' => 1, 'signatory_id' => 29, 'created_at' => now(), 'updated_at' => now()], // College of Information and Computing
            ['document_type_id' => 1, 'signatory_id' => 16, 'created_at' => now(), 'updated_at' => now()], // OSAS
            ['document_type_id' => 1, 'signatory_id' => 22, 'created_at' => now(), 'updated_at' => now()], // University Finance Division
            ['document_type_id' => 1, 'signatory_id' => 3, 'created_at' => now(), 'updated_at' => now()], // VP for Administration
            ['document_type_id' => 1, 'signatory_id' => 2, 'created_at' => now(), 'updated_at' => now()], // VP for Academic Affairs
            ['document_type_id' => 1, 'signatory_id' => 1, 'created_at' => now(), 'updated_at' => now()], // University President
        ]);

        // Planning Form (ID = 3)
        DB::table('document_type_signatories')->insert([
            ['document_type_id' => 3, 'signatory_id' => 36, 'created_at' => now(), 'updated_at' => now()], // CIC Local Council
            ['document_type_id' => 3, 'signatory_id' => 29, 'created_at' => now(), 'updated_at' => now()], // College of Information and Computing
            ['document_type_id' => 3, 'signatory_id' => 16, 'created_at' => now(), 'updated_at' => now()], // OSAS
            ['document_type_id' => 3, 'signatory_id' => 22, 'created_at' => now(), 'updated_at' => now()], // University Finance Division
            ['document_type_id' => 3, 'signatory_id' => 3, 'created_at' => now(), 'updated_at' => now()], // VP for Administration
            ['document_type_id' => 3, 'signatory_id' => 2, 'created_at' => now(), 'updated_at' => now()], // VP for Academic Affairs
            ['document_type_id' => 3, 'signatory_id' => 1, 'created_at' => now(), 'updated_at' => now()], // University President
        ]);
    }
}
