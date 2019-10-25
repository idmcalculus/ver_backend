<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPositionTypeToCareersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->enum('position_type', array('Full Time', 'Part Time'));
            $table->integer('number_of_application');
            $table->text('career_responsibilities');
            $table->date('deadline');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->dropColumn('where_you_work');
            $table->dropColumn('average_monthly_income');
            $table->dropColumn('deadline');
        });
    }
}
