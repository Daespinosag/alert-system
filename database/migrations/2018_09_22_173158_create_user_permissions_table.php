<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('alert-system')->create('user_permissions', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('user_id');
            $table->integer('permission_id');

            $table->boolean('active')->default(true);
            $table->boolean('active_email')->default(true);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permission')->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('alert-system')->dropIfExists('user_permissions');
    }
}
