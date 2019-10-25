<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvestmentEndToInvestmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investments', function (Blueprint $table) {
            $table->boolean('show_publicly')->default(false);
            $table->boolean('is_investment_ended')->default(false);
            $table->date('investment_ended_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('investments', function (Blueprint $table) {
            $table->dropColumn('show_publicly');
            $table->dropColumn('is_investment_ended');
            $table->dropColumn('investment_ended_date');
        });
    }
}
