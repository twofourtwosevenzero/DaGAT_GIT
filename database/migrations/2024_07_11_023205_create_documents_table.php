<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('DT_ID')->constrained('document_types')->onDelete('cascade');
            $table->foreignId('Status_ID')->constrained('statuses')->onDelete('cascade');
            $table->string('Description');
            $table->timestamp('Date_Created');
            $table->timestamp('Date_Approved')->nullable();
            $table->string('Document_File');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('documents');
    }
    
}
