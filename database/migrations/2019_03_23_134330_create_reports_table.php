<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id')->index();
            $table->foreign('user_id')->references('email')->on('users');
            $table->integer('investment_id')->unsigned()->index();
            $table->foreign('investment_id')->references('id')->on('investments');
            $table->string('title');
            $table->text('description');
            $table->double('returned_amount')->default(0);
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
        Schema::dropIfExists('reports');
    }
}
