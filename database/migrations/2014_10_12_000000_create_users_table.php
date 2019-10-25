<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->enum('gender', array('Male', 'Female'))->nullable();
            $table->enum('authentication_type', array('E', 'Y', 'G', 'L'))->default('E');
            $table->enum('user_category', array('SuperAdmin', 'Admin', 'User'));
            $table->text('home_address')->nullable();
            $table->text('profile_picture')->nullable();
            $table->text('country')->nullable();
            $table->text('month_of_birth')->nullable();
            $table->integer('year_of_birth')->nullable();
            $table->integer('day_of_birth')->nullable();
            $table->boolean('email_is_verified')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->boolean('updates_on_new_plans')->default(false);
            $table->boolean('email_updates_on_investment_process')->default(true);
            $table->string('password')->nullable();
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
