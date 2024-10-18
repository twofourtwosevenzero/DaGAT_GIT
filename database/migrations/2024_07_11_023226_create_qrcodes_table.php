<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQrcodesTable extends Migration
{
    public function up()
    {
        Schema::create('qrcodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Docu_ID')->constrained('documents')->onDelete('cascade');
            $table->string('QR_Image');
            $table->timestamp('Date_Created');
            $table->integer('Usage_Count')->default(0);
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('qrcodes');
    }
    
}
