<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('username', 50)->unique();
            $table->string('email', 50)->unique();
            $table->text('password');
            $table->enum('status', ['activated','suspended', 'deactivated'])->default('activated');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('user_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
