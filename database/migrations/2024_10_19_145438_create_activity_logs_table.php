<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Docu_ID')->constrained('documents')->onDelete('cascade');
            $table->foreignId('Sign_ID')->nullable()->constrained('signatories')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('requested_by')->nullable()->constrained('offices')->onDelete('set null');
            $table->string('action', 32);
            $table->timestamp('Timestamp');
            $table->string('reason', 32)->nullable();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}
