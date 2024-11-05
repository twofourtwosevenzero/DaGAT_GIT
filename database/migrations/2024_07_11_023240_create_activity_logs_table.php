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
            $table->string('action');
            $table->timestamp('Timestamp');
            $table->string('reason')->nullable();  // Added for logging reasons for specific actions
            $table->timestamps();
            $table->foreignId('requested_by')->nullable()->constrained('offices')->onDelete('set null');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
    
}
