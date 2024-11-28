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
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
