<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description');
            $table->integer('category_id')->unsigned()->index();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->string('created_by');
            $table->foreign('created_by')->references('email')->on('users');
            $table->integer('max_num_of_slots');
            $table->integer('num_of_pools_taken')->default(0);
            $table->integer('duration');
            $table->date('investment_close_date')->nullable();
            $table->double('investment_amount');
            $table->enum('expected_return_period', array('Weekly', 'Monthly'));
            $table->double('expected_return_amount');
            $table->boolean('is_investment_started')->default(false);
            $table->date('investment_started_date');
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
        Schema::dropIfExists('investments');
    }
}
