<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackingFloodAlert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('alert-system')->create('tracking_flood_alert',function(Blueprint $table){

            $table->bigIncrements('id');

            $table->bigInteger('sup_id');
            $table->bigInteger('alert_id');
            $table->bigInteger('primary_station_id');
            $table->boolean('secondary_calculate')->default(false);

            $table->float('rainfall')->nullable();
            $table->float('water_level')->nullable();
            $table->float('rainfall_recovered')->nullable();// defecto error 0

            $table->float('indicator_value')->nullable();
            $table->float('indicator_previous_difference')->nullable();

            $table->integer('alert_level')->default(0);
            $table->enum('alert_tag',['green','yellow','orange','red'])->nullable();// defecto error green
            $table->enum('alert_status',['equal','increase','decrease'])->nullable();// defecto error equal

            $table->dateTime('date_time_homogenization')->nullable();
            $table->dateTime('date_time_execution')->nullable();
            $table->dateTime('date_time_initial')->nullable();
            $table->dateTime('date_time_final')->nullable();

            $table->enum('error',['communication','no_data','no_homogenization','calculation'])->nullable();
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
        Schema::connection('alert-system')->dropIfExists('tracking_flood_alert');
    }
}
