<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignatoriesTable extends Migration
{
    public function up()
    {
        Schema::create('signatories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('QRC_ID')->constrained('qrcodes')->onDelete('cascade');
            $table->foreignId('Office_ID')->constrained('offices')->onDelete('cascade');
            $table->foreignId('Status_ID')->constrained('statuses')->onDelete('cascade');
            $table->timestamp('Received_Date')->nullable();
            $table->timestamp('Signed_Date')->nullable();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('signatories');
    }
    
}
