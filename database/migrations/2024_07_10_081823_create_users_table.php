<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50); // Allow more flexibility for user names
            $table->string('email', 50)->unique(); // Extended to accommodate longer emails
            $table->integer('otp')->nullable();
            $table->foreignId('PRI_ID')->nullable()->constrained('privileges')->onDelete('cascade'); // Nullable privilege
            $table->foreignId('Position_ID')->nullable()->constrained('positions')->onDelete('cascade'); // Nullable position
            $table->string('password', 60); // Suitable for bcrypt
            $table->rememberToken();
            $table->string('name', 32); // Optimized from default 255 
            $table->string('email', 32)->unique(); // Optimized from default 255 
            $table->integer('otp')->nullable();
            $table->foreignId('PRI_ID')->constrained('privileges')->onDelete('cascade');
            $table->foreignId('Position_ID')->constrained('positions')->onDelete('cascade');
            $table->string('password', 60); // Optimized from default 255 to 60 (bcrypt)
            $table->rememberToken(); // Default is VARCHAR(100), can keep it as is
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
