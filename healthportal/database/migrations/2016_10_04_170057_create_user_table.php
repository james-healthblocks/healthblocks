<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('role');
            $table->integer('sq_id')->nullable();
            $table->string('answer')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('api_token', 60)->unique()->nullable();
            $table->dateTime('api_created')->nullable();

            $table->integer('shc_id')->nullable();
            $table->string('region', 3)->nullable();
            $table->string('province', 3)->nullable();
            $table->string('municipality', 3)->nullable();

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
        Schema::dropIfExists('user');
    }
}
