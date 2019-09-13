<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackingLandslideAlert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('alert-system')->create('tracking_landslide_alert',function(Blueprint $table){

            $table->bigIncrements('id');

            $table->bigInteger('zone_id');
            $table->bigInteger('alert_flood_id');
            $table->bigInteger('station_id');

            $table->float('rainfall')->nullable();
            $table->float('rainfall_recovered')->nullable();

            $table->float('alert_value')->nullable();
            $table->float('alert_previous_difference')->nullable();
            $table->enum('alert_tag',['green','yellow','orange','red'])->nullable();
            $table->enum('alert_status',['equal','increase','decrease'])->nullable();

            $table->dateTime('date_execution')->nullable();
            $table->dateTime('date_initial')->nullable();
            $table->dateTime('date_final')->nullable();

            $table->enum('error',['communication','no_data','calculation'])->nullable();
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
        Schema::connection('alert-system')->dropIfExists('tracking_landslide_alert');
    }
}
