<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestInvestmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_investments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id')->index();
            $table->foreign('user_id')->references('email')->on('users');
            $table->integer('investment_id')->unsigned()->index();
            $table->foreign('investment_id')->references('id')->on('investments');
            $table->integer('approved')->default(0);
            $table->date('approved_date')->nullable();
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
        Schema::dropIfExists('request_investments');
    }
}
