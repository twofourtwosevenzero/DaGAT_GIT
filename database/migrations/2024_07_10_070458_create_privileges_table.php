<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivilegesTable extends Migration
{
    public function up()
    {
        Schema::create('privileges', function (Blueprint $table) {
            $table->id();
            $table->string('Privilege_Level');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('privileges');
    }
}
