<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('alert-system')->create('users', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('role_id');

            $table->string('name');
            $table->string('institution')->nullable();
            $table->string('email')->unique();

            $table->boolean('confirmed')->default(false);
            $table->string('confirmed_code')->nullable();
            $table->boolean('accepted')->default(false);

            $table->string('password',500);

            $table->rememberToken();
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('role')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('alert-system')->dropIfExists('users');
    }
}
