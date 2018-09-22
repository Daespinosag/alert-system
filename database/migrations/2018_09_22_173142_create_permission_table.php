<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('alert-system')->create('permission', function (Blueprint $table) {

            $table->increments('id');

            $table->string('name');
            $table->string('code')->unique();
            $table->string('description');
            $table->string('type');

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
        Schema::connection('alert-system')->dropIfExists('permission');
    }
}
