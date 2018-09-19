<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Flood extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('alert-system')->create('flood',function(Blueprint $table)
        {
            $table->increments('id');

            $table->integer('station');
            $table->float('a10_value')->nullable();
            $table->integer('alert')->nullable();
            $table->float('avg_recovered')->nullable();
            $table->float('dif_previous_a10')->nullable();
            $table->integer('num_not_change_alert')->nullable();

            $table->boolean('change_alert')->default(false);
            $table->boolean('alert_decrease')->default(false);
            $table->boolean('alert_increase')->default(false);
            $table->boolean('error')->default(false);

            $table->dateTime('date_execution')->nullable();
            $table->dateTime('date_initial')->nullable();
            $table->dateTime('date_final')->nullable();

            $table->string('comment')->nullable();

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
        Schema::connection('alert-system')->dropIfExists('flood');
    }
}
