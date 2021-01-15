<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateControlNewDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('alert-system')->create('control_new_data', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->bigInteger('alert_id');
            $table->string('alert_code');
            $table->string('date');
            $table->string('time');
            $table->boolean('active');
            $table->boolean('homogenization');

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
        Schema::connection('alert-system')->dropIfExists('control_new_data');
    }
}
