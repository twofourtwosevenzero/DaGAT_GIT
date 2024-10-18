<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DropAllTables extends Command
{
    protected $signature = 'db:drop-all-tables';
    protected $description = 'Drop all tables in the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Dropping all tables...');

        Schema::disableForeignKeyConstraints();

        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $tableName = $table->dagat; // replace 'your_database_name' with the actual database name
            Schema::drop($tableName);
        }

        Schema::enableForeignKeyConstraints();

        $this->info('All tables dropped successfully.');
    }
}
