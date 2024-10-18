<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovedFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approved_files', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // File name
            $table->string('path'); // File path in storage
            $table->foreignId('document_type_id')->constrained('document_types')->onDelete('cascade'); // Reference to document type
            $table->date('approved_date'); // Date of approval
            $table->timestamps(); // Created and Updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approved_files');
    }
}
